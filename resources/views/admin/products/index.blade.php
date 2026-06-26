@extends('layouts.kepala_toko')
@section('page-title','Produk')
@section('content')

{{-- Header bar --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h6 class="fw-bold mb-0">Daftar Produk</h6>
        <small class="text-muted">Kelola semua produk yang tersedia di toko.</small>
    </div>
    <a href="{{ route('kepala-toko.products.create') }}" class="btn btn-tb-primary">
        <i class="bi bi-plus-lg me-1"></i> Produk Baru
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table admin-table mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width:64px;"></th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td>
                        <img src="{{ $p->primary_image_url }}"
                             width="48" height="48"
                             class="rounded-3"
                             style="object-fit:cover;"
                             onerror="this.src='https://placehold.co/48x48/e8f5e9/2D5016?text=B'">
                    </td>
                    <td>
                        <span class="fw-semibold d-block">{{ $p->name }}</span>
                        <small class="text-muted">{{ $p->weight_label }}</small>
                    </td>
                    <td>
                        <span class="text-muted small">{{ $p->subCategory->category->name }}</span><br>
                        <span class="admin-badge bg-primary" style="font-size:11px;">{{ $p->subCategory->name }}</span>
                    </td>
                    <td class="fw-semibold">Rp {{ number_format($p->price,0,',','.') }}</td>
                    <td class="text-center">
                        @if($p->stock <= 5)
                            <span class="admin-badge bg-danger">{{ $p->stock }}</span>
                        @elseif($p->stock <= 20)
                            <span class="admin-badge bg-warning">{{ $p->stock }}</span>
                        @else
                            <span class="admin-badge bg-success">{{ $p->stock }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($p->is_active)
                            <span class="admin-badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                        @else
                            <span class="admin-badge bg-secondary"><i class="bi bi-pause-circle me-1"></i>Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('kepala-toko.products.edit',$p) }}" class="btn btn-sm btn-tb-outline" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('kepala-toko.products.destroy',$p) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-box-seam fs-2 d-block mb-2 opacity-25"></i>
                        Belum ada produk. <a href="{{ route('kepala-toko.products.create') }}" class="text-tb-green">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="card-body pt-2">{{ $products->links() }}</div>
    @endif
</div>

@endsection
