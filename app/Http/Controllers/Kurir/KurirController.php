<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurirController extends Controller
{
    /**
     * Tampilan Daftar Tugas Pengiriman
     */
    public function index()
    {
        $kurirId = auth()->id();
        
        // Tugas yang ditugaskan kepada kurir ini (dikirim, diproses, selesai, dsb)
        $tasks = Order::where('courier_id', $kurirId)
            ->latest()
            ->paginate(15);

        return view('kurir.dashboard', compact('tasks'));
    }

    /**
     * Tampilan Detail Tugas Pengiriman
     */
    public function show(Order $order)
    {
        if ($order->courier_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['user', 'items.product']);
        return view('kurir.show', compact('order'));
    }

    /**
     * Perbarui Status Pengiriman (misalnya dari diproses ke dikirim atau selesai)
     */
    public function updateStatus(Request $request, Order $order)
    {
        if ($order->courier_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'status' => 'required|in:dikirim,selesai'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($newStatus === 'selesai' && !$order->delivery_proof_url) {
            return redirect()->back()->with('error', 'Gagal: Anda wajib mengunggah foto bukti pengiriman terlebih dahulu sebelum menandai pengiriman selesai.');
        }

        if ($newStatus === 'selesai' && $order->payment_method === 'cod') {
            return redirect()->back()->with('error', 'Gagal: Pesanan dengan metode COD wajib diselesaikan menggunakan tombol Konfirmasi Terima Uang COD.');
        }

        $order->update(['status' => $newStatus]);

        // Audit Log
        AuditLog::log(
            auth()->user(),
            'Update Status Pengiriman',
            "Mengubah status pengiriman pesanan #{$order->order_number} dari '{$oldStatus}' menjadi '{$newStatus}'."
        );

        return redirect()->back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    /**
     * Unggah Foto Bukti Pengiriman
     */
    public function uploadProof(Request $request, Order $order)
    {
        if ($order->courier_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'delivery_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('delivery_proof')) {
            if ($order->delivery_proof_url) {
                Storage::disk('public')->delete($order->delivery_proof_url);
            }
            $path = $request->file('delivery_proof')->store('delivery_proofs', 'public');
            $order->update(['delivery_proof_url' => $path]);

            // Audit Log
            AuditLog::log(
                auth()->user(),
                'Upload Bukti Pengiriman',
                "Mengunggah foto bukti pengiriman untuk pesanan #{$order->order_number}."
            );
        }

        return redirect()->back()->with('success', 'Foto bukti pengiriman berhasil diunggah.');
    }

    /**
     * Konfirmasi Pembayaran COD oleh Kurir
     */
    public function confirmCodPayment(Order $order)
    {
        if ($order->courier_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($order->payment_method !== 'cod') {
            return redirect()->back()->with('error', 'Metode pembayaran bukan COD.');
        }

        if (!$order->delivery_proof_url) {
            return redirect()->back()->with('error', 'Gagal: Anda wajib mengunggah foto bukti pengiriman terlebih dahulu sebelum mengonfirmasi pembayaran COD.');
        }

        // Update status ke selesai dan pembayaran dianggap lunas/dikonfirmasi
        $order->update([
            'status' => 'selesai',
        ]);

        // Audit Log
        AuditLog::log(
            auth()->user(),
            'Konfirmasi Pembayaran COD',
            "Mengkonfirmasi pembayaran COD senilai Rp " . number_format($order->total, 0, ',', '.') . " untuk pesanan #{$order->order_number}."
        );

        return redirect()->back()->with('success', 'Pembayaran COD berhasil dikonfirmasi dan pesanan ditandai selesai.');
    }
}
