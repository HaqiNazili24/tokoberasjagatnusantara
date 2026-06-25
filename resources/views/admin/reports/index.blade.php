@extends('layouts.kepala_toko')
@section('page-title','Laporan Penjualan')
@section('content')

{{-- Filter Bar --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-funnel me-2 text-tb-green"></i>Filter Laporan</h6>
        <form class="row g-2 align-items-end" method="GET">
            <div class="col-md-3">
                <label class="form-label fw-semibold small mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small mb-1">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $k => $v)
                        <option value="{{ $k }}" @selected(($filters['status'] ?? '') === $k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small mb-1">Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(($filters['category_id'] ?? '') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-tb-primary flex-grow-1">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('kepala-toko.reports.pdf', $filters) }}" class="btn btn-outline-danger" title="Ekspor PDF">
                    <i class="bi bi-file-pdf"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Summary Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card admin-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="admin-stat-label">Total Pesanan</p>
                        <h3 class="admin-stat-value">{{ $totalOrders }}</h3>
                        <span class="admin-stat-suffix">pesanan dalam periode ini</span>
                    </div>
                    <div class="admin-stat-icon" style="background:rgba(46,125,50,0.08); color:#2E7D32;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card admin-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="admin-stat-label">Total Pendapatan</p>
                        <h3 class="admin-stat-value">Rp {{ number_format($totalRevenue,0,',','.') }}</h3>
                        <span class="admin-stat-suffix">pendapatan bersih</span>
                    </div>
                    <div class="admin-stat-icon" style="background:rgba(46,125,50,0.08); color:#2E7D32;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-body pb-0">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0">Rincian Pesanan</h6>
            <small class="text-muted">{{ count($orders) }} data ditampilkan</small>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table admin-table mb-0 align-middle">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td><span class="fw-semibold text-tb-green">{{ $o->order_number }}</span></td>
                    <td class="text-muted small">{{ $o->created_at->format('d/m/Y') }}</td>
                    <td>{{ $o->user->full_name }}</td>
                    <td>
                        <small class="text-muted">
                            @foreach($o->items as $it)
                                {{ $it->product_name_snapshot }}@if(!$loop->last), @endif
                            @endforeach
                        </small>
                    </td>
                    <td class="text-center">{{ $o->items->sum('quantity') }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($o->total,0,',','.') }}</td>
                    <td><span class="admin-badge bg-{{ $o->status_color }}">{{ $o->status_label }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-bar-chart-line fs-2 d-block mb-2 opacity-25"></i>
                        Tidak ada data untuk filter yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
