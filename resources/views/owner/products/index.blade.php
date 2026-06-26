@extends('layouts.owner')
@section('page-title','Kelola Produk')
@section('content')

{{-- Header bar --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #006241;">Daftar Produk Beras</h4>
        <p class="text-muted mb-0">Kelola semua produk beras beserta harga dan stoknya.</p>
    </div>
    <a href="{{ route('owner.products.create') }}" class="btn" style="background-color: #00754A; color: white; border-radius: 50px; padding: 10px 24px; font-weight: 600; border: none; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead style="background-color: #edebe9; color: #1E3932;">
                <tr>
                    <th style="width:72px; padding: 16px;" class="ps-4">Foto</th>
                    <th style="padding: 16px;">Nama Produk</th>
                    <th style="padding: 16px;">Kategori / Tipe</th>
                    <th style="padding: 16px;">Harga</th>
                    <th class="text-center" style="padding: 16px;">Stok</th>
                    <th class="text-center" style="padding: 16px;">Status</th>
                    <th style="padding: 16px;" class="pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr style="border-bottom: 1px solid #edebe9;">
                    <td class="ps-4" style="padding: 12px 16px;">
                        <img src="{{ $p->primary_image_url }}"
                             width="56" height="56"
                             class="rounded-3 shadow-sm"
                             style="object-fit:cover;"
                             onerror="this.src='https://placehold.co/56x56/e8f5e9/2D5016?text=Beras'">
                    </td>
                    <td style="padding: 12px 16px;">
                        <span class="fw-bold d-block" style="color: #1E3932; font-size: 16px;">{{ $p->name }}</span>
                        <small class="text-muted">{{ $p->weight_label }}</small>
                    </td>
                    <td style="padding: 12px 16px;">
                        <span class="text-muted small d-block mb-1">{{ $p->subCategory->category->name }}</span>
                        <span class="badge" style="background-color: #d4e9e2; color: #006241; font-weight: 600; font-size: 12px; padding: 6px 12px; border-radius: 20px;">{{ $p->subCategory->name }}</span>
                    </td>
                    <td class="fw-bold" style="color: #00754A; padding: 12px 16px;">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </td>
                    <td class="text-center" style="padding: 12px 16px;">
                        @if($p->stock <= 5)
                            <span class="badge bg-danger rounded-pill px-3 py-2" style="font-size: 13px;">{{ $p->stock }} (Kritis)</span>
                        @elseif($p->stock <= 20)
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2" style="font-size: 13px;">{{ $p->stock }} (Limit)</span>
                        @else
                            <span class="badge bg-success rounded-pill px-3 py-2" style="font-size: 13px;">{{ $p->stock }}</span>
                        @endif
                    </td>
                    <td class="text-center" style="padding: 12px 16px;">
                        @if($p->is_active)
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #d4e9e2; color: #006241; font-weight: 600;"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="bi bi-pause-circle-fill me-1"></i>Nonaktif</span>
                        @endif
                    </td>
                    <td class="pe-4" style="padding: 12px 16px;">
                        <div class="d-flex gap-2">
                            <a href="{{ route('owner.products.edit',$p) }}" class="btn btn-sm btn-outline-success" style="border-radius: 50px; padding: 6px 16px;" title="Edit">
                                <i class="bi bi-pencil-fill me-1"></i> Edit
                            </a>
                            <form action="{{ route('owner.products.destroy',$p) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" style="border-radius: 50px; padding: 6px 16px;" title="Hapus">
                                    <i class="bi bi-trash3-fill me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5" style="background-color: white;">
                        <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-50" style="color: #006241;"></i>
                        Belum ada produk beras terdaftar. <a href="{{ route('owner.products.create') }}" class="fw-bold" style="color: #00754A;">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="card-body pt-3 pb-3 border-top" style="background-color: white;">
        {{ $products->links() }}
    </div>
    @endif
</div>

@endsection
