<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show()
    {
        $items = Cart::with('product')->where('user_id', auth()->id())->get();
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');

        $addresses = Address::where('user_id', auth()->id())->orderByDesc('is_default')->get();
        $subtotal = $items->sum(fn($i) => $i->subtotal);
        $shipping = (int) config('app.store.shipping_flat_rate');
        $total = $subtotal + $shipping;

        return view('customer.checkout.show', compact('items', 'addresses', 'subtotal', 'shipping', 'total'));
    }

    public function place(Request $request)
    {
        $data = $request->validate([
            'address_id' => ['nullable', 'exists:addresses,id'],
            'recipient_name' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'phone' => ['required_without:address_id', 'nullable', 'numeric', 'digits_between:10,15'],
            'full_address' => ['required_without:address_id', 'nullable', 'string'],
            'city' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'province' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'postal_code' => ['required_without:address_id', 'nullable', 'string', 'max:10'],
            'save_address' => ['nullable'],
            'payment_method' => ['required', 'in:transfer,cod'],
        ]);

        $userId = auth()->id();
        $items = Cart::with('product')->where('user_id', $userId)->get();
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');

        // Validasi ketersediaan stok sebelum membuat pesanan
        foreach ($items as $item) {
            if (! $item->product || ! $item->product->is_active) {
                return redirect()->route('cart.index')->with('error', "Produk \"{$item->product?->name}\" sudah tidak tersedia. Silakan hapus dari keranjang.");
            }
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', "Stok \"{$item->product->name}\" tidak mencukupi (tersisa {$item->product->stock}).");
            }
        }

        $address = null;
        if (! empty($data['address_id'])) {
            $address = Address::where('user_id', $userId)->findOrFail($data['address_id']);
        } else {
            if ($request->boolean('save_address')) {
                $address = Address::create([
                    'user_id' => $userId, 'label' => 'Alamat',
                    'recipient_name' => $data['recipient_name'], 'phone' => $data['phone'],
                    'full_address' => $data['full_address'], 'city' => $data['city'],
                    'province' => $data['province'], 'postal_code' => $data['postal_code'],
                    'is_default' => Address::where('user_id', $userId)->doesntExist(),
                ]);
            }
        }

        $paymentMethod = $data['payment_method'];

        $order = DB::transaction(function () use ($items, $userId, $address, $data, $paymentMethod) {
            $subtotal = $items->sum(fn($i) => $i->subtotal);
            $shipping = (int) config('app.store.shipping_flat_rate');

            // COD langsung masuk status 'diproses', transfer harus upload bukti dulu
            $initialStatus = $paymentMethod === 'cod' ? 'diproses' : 'menunggu_pembayaran';

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'address_id' => $address?->id,
                'shipping_recipient' => $address?->recipient_name ?? $data['recipient_name'],
                'shipping_phone' => $address?->phone ?? $data['phone'],
                'shipping_address' => $address?->full_address ?? $data['full_address'],
                'shipping_city' => $address?->city ?? $data['city'],
                'shipping_province' => $address?->province ?? $data['province'],
                'shipping_postal_code' => $address?->postal_code ?? $data['postal_code'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $subtotal + $shipping,
                'payment_method' => $paymentMethod,
                'status' => $initialStatus,
            ]);

            foreach ($items as $it) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $it->product_id,
                    'product_name_snapshot' => $it->product->name,
                    'price_snapshot' => $it->product->price,
                    'quantity' => $it->quantity,
                    'subtotal' => $it->subtotal,
                ]);

                // Kurangi stok produk secara real-time
                $it->product->decrement('stock', $it->quantity);
            }

            Cart::where('user_id', $userId)->delete();
            return $order;
        });

        $message = $paymentMethod === 'cod'
            ? 'Pesanan dibuat dengan metode COD. Siapkan pembayaran saat barang diterima.'
            : 'Pesanan dibuat. Silakan upload bukti pembayaran.';

        return redirect()->route('orders.show', $order)->with('success', $message);
    }
}
