<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class KepalaTokoController extends Controller
{
    /**
     * Tampilan Dashboard & Pemantauan Operasional Kepala Toko
     * (Tugas 1, 4, 5, 10, 14)
     */
    public function index()
    {
        // 1. Mengecek Laporan Penjualan (Tugas 4, 10, 14)
        $totalRevenue = Order::where('status', 'selesai')->sum('total');
        $totalOrders = Order::count();
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // 2. Pemantauan Stok & Kontrol Stok Minimum (Tugas 2, 11)
        // Kita anggap stok minimum peringatan adalah di bawah 10 unit
        $lowStockProducts = Product::where('stock', '<', 10)->get();
        $allProducts = Product::orderBy('stock', 'asc')->get();

        // 3. Menghitung Keuntungan Kasar (Tugas 14)
        // Estimasi margin profit 15% dari total penjualan
        $estimatedProfit = $totalRevenue * 0.15; 

        return view('kepala-toko.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'recentOrders',
            'lowStockProducts',
            'allProducts',
            'estimatedProfit'
        ));
    }

    /**
     * Menyetujui Pembelian Stok Beras dari Supplier (Tugas 6)
     */
    public function approvePurchase(Request $request)
    {
        // Logika menyetujui transaksi pembelian dari supplier
        return redirect()->back()->with('success', 'Pembelian stok beras dari supplier berhasil disetujui.');
    }

    /**
     * Mengatur Harga Jual & Program Diskon (Tugas 7)
     */
    public function updatePriceAndDiscount(Request $request, Product $product)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            // Pilihan diskon bisa ditambahkan pada modifikasi berikutnya
        ]);

        $product->update([
            'price' => $request->price
        ]);

        return redirect()->back()->with('success', 'Harga jual beras berhasil diperbarui.');
    }

    /**
     * Memverifikasi Transaksi Retur / Penukaran Barang (Tugas 9)
     */
    public function verifyRetur(Request $request, Order $order)
    {
        // Logika verifikasi pengembalian beras yang rusak/salah kirim
        return redirect()->back()->with('success', 'Transaksi retur/penukaran beras berhasil diverifikasi.');
    }
}
