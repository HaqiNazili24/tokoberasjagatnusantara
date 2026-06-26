@extends('layouts.owner')
@section('page-title','Audit Log')
@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: #006241;">Laporan Audit Log (Aktivitas Tim)</h4>
    <p class="text-muted">Pantau transparansi seluruh aktivitas operasional yang dilakukan oleh Karyawan dan Kurir.</p>
</div>

{{-- Filter Card --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-4">
        <form action="{{ route('owner.audit-logs') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Filter Berdasarkan Peran</label>
                <select name="role" class="form-select" style="border-radius: 8px;">
                    <option value="">-- Semua Peran --</option>
                    <option value="karyawan" @selected(request('role') == 'karyawan')>Karyawan</option>
                    <option value="kurir" @selected(request('role') == 'kurir')>Kurir</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Cari Jenis Aksi / Detail</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;"><i class="bi bi-search"></i></span>
                    <input type="text" name="action" class="form-control" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;" placeholder="Contoh: Stok, COD, Pembayaran..." value="{{ request('action') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn text-white w-100" style="background-color: #00754A; border-radius: 50px; padding: 10px; font-weight: 600; border: none;">
                    Terapkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Log Table --}}
<div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead style="background-color: #edebe9; color: #1E3932;">
                <tr>
                    <th style="padding: 16px;" class="ps-4">Waktu Kejadian</th>
                    <th style="padding: 16px;">Nama Pengguna</th>
                    <th style="padding: 16px;">Peran</th>
                    <th style="padding: 16px;">Jenis Aksi</th>
                    <th style="padding: 16px; width: 45%;">Detail Perubahan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr style="border-bottom: 1px solid #edebe9;">
                    <td class="ps-4 text-muted" style="padding: 12px 16px;">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                        <small class="d-block text-muted-soft">{{ $log->created_at->diffForHumans() }}</small>
                    </td>
                    <td style="padding: 12px 16px;">
                        <span class="fw-bold" style="color: #1E3932;">{{ $log->user_name }}</span>
                    </td>
                    <td style="padding: 12px 16px;">
                        @if($log->role === 'karyawan')
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #d4e9e2; color: #006241; font-weight: 600;">Karyawan</span>
                        @elseif($log->role === 'kurir')
                            <span class="badge rounded-pill px-3 py-2 text-dark" style="background-color: #faf6ee; border: 1px solid #dfc49d; font-weight: 600;">Kurir</span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $log->role }}</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px;">
                        <span class="badge rounded-pill px-3 py-2 bg-dark">{{ $log->action_type }}</span>
                    </td>
                    <td style="padding: 12px 16px;">
                        <span class="text-dark small" style="white-space: pre-line; line-height: 1.4;">{{ $log->changes_detail }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted" style="background-color: white;">
                        <i class="bi bi-journals fs-1 d-block mb-3 opacity-50" style="color: #006241;"></i>
                        Tidak ada catatan log aktivitas yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-body pt-3 pb-3 border-top" style="background-color: white;">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection
