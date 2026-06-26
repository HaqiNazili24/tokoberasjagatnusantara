<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Dashboard Karyawan
     */
    public function index()
    {
        $products = Product::with('subCategory.category')->orderBy('stock', 'asc')->paginate(15);
        $orders = Order::with('user')->latest()->paginate(15);
        return view('karyawan.dashboard', compact('products', 'orders'));
    }

    /**
     * Update Stok Produk
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $oldStock = $product->stock;
        $newStock = $request->stock;

        $product->update(['stock' => $newStock]);

        // Audit Log
        AuditLog::log(
            auth()->user(),
            'Update Stok',
            "Mengubah stok produk '{$product->name}' dari {$oldStock} menjadi {$newStock}."
        );

        return redirect()->back()->with('success', 'Stok produk berhasil diperbarui.');
    }

    /**
     * Tampilan Detail Pesanan
     */
    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product', 'courier']);
        $couriers = User::where('role', 'kurir')->get();
        return view('karyawan.orders.show', compact('order', 'couriers'));
    }

    /**
     * Konfirmasi Pembayaran
     */
    public function confirmPayment(Order $order)
    {
        $order->update(['status' => 'pembayaran_dikonfirmasi']);

        // Audit Log
        AuditLog::log(
            auth()->user(),
            'Konfirmasi Pembayaran',
            "Mengkonfirmasi pembayaran transfer untuk pesanan #{$order->order_number}."
        );

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    /**
     * Tolak Pembayaran
     */
    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $order->update([
            'status' => 'menunggu_pembayaran',
            'rejection_reason' => $request->rejection_reason,
            'payment_proof_url' => null // Hapus bukti pembayaran lama agar bisa upload ulang
        ]);

        // Audit Log
        AuditLog::log(
            auth()->user(),
            'Tolak Pembayaran',
            "Menolak pembayaran transfer pesanan #{$order->order_number} dengan alasan: {$request->rejection_reason}."
        );

        return redirect()->back()->with('success', 'Pembayaran ditolak. Pelanggan harus mengunggah ulang bukti pembayaran.');
    }

    /**
     * Perbarui Status Pesanan & Tugaskan Kurir
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,pembayaran_dikirim,pembayaran_dikonfirmasi,diproses,dikirim,selesai,dibatalkan',
            'courier_id' => 'nullable|exists:users,id'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];

        if ($request->has('courier_id')) {
            $updateData['courier_id'] = $request->courier_id;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $updateData, $oldStatus, $newStatus) {
            $order->update($updateData);

            // Kembalikan stok jika dibatalkan
            if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }
            // Kurangi kembali stok jika dipulihkan dari dibatalkan
            elseif ($oldStatus === 'dibatalkan' && $newStatus !== 'dibatalkan') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->decrement('stock', $item->quantity);
                    }
                }
            }
        });

        // Audit Log
        $logDetail = "Mengubah status pesanan #{$order->order_number} dari '{$oldStatus}' menjadi '{$newStatus}'.";
        if ($request->courier_id) {
            $courier = User::find($request->courier_id);
            $logDetail .= " Menugaskan Kurir: {$courier->full_name}.";
        }
        AuditLog::log(auth()->user(), 'Update Status Pesanan', $logDetail);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
