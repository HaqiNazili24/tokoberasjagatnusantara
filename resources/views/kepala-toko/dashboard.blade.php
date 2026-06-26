@extends('layouts.kepala_toko')
@section('page-title', 'Dashboard Kepala Toko')
@section('content')

{{-- Statistik & Evaluasi Bisnis (Tugas 1, 4, 10, 14) --}}
<div class="row g-3 mb-4">
    @php
        $cards = [
            [
                'label'   => 'Total Pesanan Beras',
                'value'   => $totalOrders,
                'icon'    => 'bi-receipt',
                'color'   => '#1565C0',
                'bg'      => 'rgba(21,101,192,0.08)',
            ],
            [
                'label'   => 'Total Omzet Penjualan',
                'value'   => 'Rp '.number_format($totalRevenue, 0, ',', '.'),
                'icon'    => 'bi-cash-coin',
                'color'   => '#2E7D32',
                'bg'      => 'rgba(46,125,50,0.08)',
            ],
            [
                'label'   => 'Estimasi Keuntungan Bersih (15%)',
                'value'   => 'Rp '.number_format($estimatedProfit, 0, ',', '.'),
                'icon'    => 'bi-graph-up-arrow',
                'color'   => '#2E7D32',
                'bg'      => 'rgba(46,125,50,0.08)',
            ],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="col-12 col-md-4">
        <div class="card admin-stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="admin-stat-label">{{ $card['label'] }}</p>
                        <h3 class="admin-stat-value">{{ $card['value'] }}</h3>
                    </div>
                    <div class="admin-stat-icon" style="background:{{ $card['bg'] }}; color:{{ $card['color'] }};">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Peringatan Stok Minimum Beras & Reorder (Tugas 2, 11) --}}
@if($lowStockProducts->count() > 0)
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
    <div>
        <h6 class="alert-heading fw-bold mb-1">Peringatan: Stok Beras Menipis (Di bawah batas minimum)!</h6>
        <span>Segera lakukan pemesanan ulang (Reorder) ke supplier untuk beras berikut agar tidak kehabisan barang.</span>
    </div>
</div>
@endif

<div class="row g-4">
    {{-- Tabel Monitoring Stok Beras (Tugas 2, 3, 11) --}}
    <div class="col-12 col-lg-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Monitoring & Kontrol Stok Beras</h6>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nama Beras</th>
                            <th>Stok Saat Ini</th>
                            <th>Status Stok</th>
                            <th>Ubah Harga Jual (Tugas 7)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allProducts as $p)
                        <tr>
                            <td class="fw-semibold">{{ $p->name }}</td>
                            <td>{{ $p->stock }} {{ $p->weight_label }}</td>
                            <td>
                                @if($p->stock < 10)
                                    <span class="badge bg-danger">Habis / Segera Reorder</span>
                                @elseif($p->stock < 25)
                                    <span class="badge bg-warning text-dark">Stok Menipis</span>
                                @else
                                    <span class="badge bg-success">Stok Aman</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('kepala-toko.products.price', $p) }}" method="POST" class="d-flex gap-2 align-items-center">
                                    @csrf
                                    <div class="input-group input-group-sm" style="max-width: 150px;">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price" value="{{ (int)$p->price }}" class="form-control" placeholder="Harga">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kolom Otorisasi & Kontrol Operasional Lainnya (Tugas 6, 8, 9) --}}
    <div class="col-12 col-lg-4">
        {{-- Konfirmasi Pembelian Supplier (Tugas 6) --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-check me-2"></i>Persetujuan Pembelian Supplier (Tugas 6)</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Setujui pengadaan beras baru yang diajukan oleh bagian Administrasi dari supplier rekanan.</p>
                <form action="{{ route('kepala-toko.purchase.approve') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Pengajuan Supplier</label>
                        <select class="form-select form-select-sm" required>
                            <option value="">-- Pilih Pengajuan Stok Beras --</option>
                            <option value="1">Beras Rojo Lele (50 Sak @ 25kg) - Supplier Indramayu</option>
                            <option value="2">Beras Idola Premium (30 Sak @ 25kg) - Supplier Karawang</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-check-circle me-1"></i>Setujui Pembelian</button>
                </form>
            </div>
        </div>

        {{-- Retur Barang & Keluhan Kualitas (Tugas 8, 9) --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-counterclockwise me-2"></i>Verifikasi Retur Beras (Tugas 9)</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small">Otorisasi permohonan retur beras yang rusak atau cacat dari pelanggan agar ditukar dengan yang baru.</p>
                <div class="p-3 bg-light rounded border mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold small">Order #TRX9012</span>
                        <span class="badge bg-warning text-dark small">Pending Retur</span>
                    </div>
                    <p class="mb-2 small text-muted">Pelanggan mengeluhkan beras Rojo Lele 25kg berbau apek/basah.</p>
                    <form action="#" method="POST" onsubmit="alert('Retur berhasil diverifikasi!'); return false;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger w-100"><i class="bi bi-check-lg me-1"></i>Verifikasi & Tukar Barang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
