@extends('layouts.kurir')
@section('title', 'Tugas Pengiriman')
@section('content')

<div class="mb-4">
    <h3 class="fw-bold mb-1" style="color: #1E3932;">Daftar Tugas Pengantaran</h3>
    <p class="text-muted">Kelola pengiriman beras yang ditugaskan kepada Anda secara real-time.</p>
</div>

<div class="row g-3">
    @forelse($tasks as $task)
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; background-color: white; border-left: 5px solid {{ $task->status === 'selesai' ? '#2e7d32' : ($task->status === 'dikirim' ? '#0288d1' : '#fbc02d') }} !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold" style="color: #1E3932; font-size: 17px;">#{{ $task->order_number }}</span>
                    <span class="badge bg-{{ $task->status_color }} rounded-pill px-3 py-1" style="font-size: 11px;">
                        {{ $task->status_label }}
                    </span>
                </div>

                <div class="mb-3 small">
                    <div class="d-flex align-items-start gap-2 mb-2 text-dark">
                        <i class="bi bi-person-fill text-muted"></i>
                        <div>
                            <strong>Penerima:</strong> {{ $task->shipping_recipient }}
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2 mb-2 text-dark">
                        <i class="bi bi-geo-alt-fill text-muted"></i>
                        <div>
                            <strong>Alamat:</strong> {{ $task->shipping_address }}, {{ $task->shipping_city }}
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2 text-dark">
                        <i class="bi bi-cash-coin text-muted"></i>
                        <div>
                            <strong>Metode:</strong> <span class="text-uppercase fw-semibold">{{ $task->payment_method }}</span> &middot; <strong class="text-success">Rp {{ number_format($task->total,0,',','.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <a href="{{ route('kurir.orders.show', $task) }}" class="btn text-white" style="background-color: #00754A; border-radius: 50px; font-weight: 600; font-size: 13px; padding: 8px 16px;">
                        Buka Detail Tugas &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm py-5 text-center" style="border-radius: 12px; background-color: white;">
            <div class="card-body">
                <i class="bi bi-bicycle fs-1 opacity-25 d-block mb-3" style="color: #006241;"></i>
                <h5 class="fw-bold" style="color: #1E3932;">Tidak Ada Tugas Pengiriman</h5>
                <p class="text-muted">Belum ada tugas pengantaran beras yang ditugaskan kepada Anda.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($tasks->hasPages())
<div class="mt-4">
    {{ $tasks->links() }}
</div>
@endif

@endsection
