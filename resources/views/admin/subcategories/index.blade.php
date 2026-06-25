@extends('layouts.kepala_toko')
@section('page-title','Sub Kategori')
@section('content')

<div class="row g-3">

    {{-- Add Sub-Category Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-1">Tambah Sub Kategori</h6>
                <p class="text-muted small mb-3">Kelompokkan produk lebih spesifik di bawah kategori utama.</p>
                <form action="{{ route('kepala-toko.sub-categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Induk</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Sub Kategori</label>
                        <input name="name" class="form-control" placeholder="Contoh: Premium, Medium" required>
                    </div>
                    <button class="btn btn-tb-primary w-100">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Sub Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Sub-Categories Table --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-body pb-0">
                <h6 class="fw-bold mb-0">Daftar Sub Kategori</h6>
                <small class="text-muted">Semua sub kategori yang tersedia.</small>
            </div>
            <div class="table-responsive mt-3">
                <table class="table admin-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Kategori Induk</th>
                            <th>Sub Kategori</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subCategories as $s)
                        <tr>
                            <td>
                                <span class="admin-badge bg-primary">{{ $s->category->name }}</span>
                            </td>
                            <td class="fw-semibold">{{ $s->name }}</td>
                            <td>
                                <form action="{{ route('kepala-toko.sub-categories.destroy',$s) }}" method="POST" onsubmit="return confirm('Hapus sub kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <i class="bi bi-tag fs-2 d-block mb-2 opacity-25"></i>
                                Belum ada sub kategori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($subCategories->hasPages())
            <div class="card-body pt-2">{{ $subCategories->links() }}</div>
            @endif
        </div>
    </div>

</div>
@endsection
