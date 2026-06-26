@extends('layouts.kepala_toko')
@section('page-title','Kategori')
@section('content')

<div class="row g-3">

    {{-- Add Category Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-1">Tambah Kategori Baru</h6>
                <p class="text-muted small mb-3">Kategori utama untuk pengelompokan produk.</p>
                <form action="{{ route('kepala-toko.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input name="name" class="form-control" placeholder="Contoh: Beras, Minyak Goreng" required>
                    </div>
                    <button class="btn btn-tb-primary w-100">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-body pb-0">
                <h6 class="fw-bold mb-0">Daftar Kategori</h6>
                <small class="text-muted">Klik simpan untuk mengubah nama kategori.</small>
            </div>
            <div class="table-responsive mt-3">
                <table class="table admin-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th class="text-center">Sub Kategori</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $c)
                        <tr>
                            <td>
                                <form action="{{ route('kepala-toko.categories.update',$c) }}" method="POST" class="d-flex gap-2">
                                    @csrf @method('PATCH')
                                    <input name="name" value="{{ $c->name }}" class="form-control form-control-sm">
                                    <button class="btn btn-sm btn-tb-outline flex-shrink-0" title="Simpan">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <span class="admin-badge bg-primary">{{ $c->sub_categories_count }}</span>
                            </td>
                            <td>
                                <form action="{{ route('kepala-toko.categories.destroy',$c) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
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
                                <i class="bi bi-tags fs-2 d-block mb-2 opacity-25"></i>
                                Belum ada kategori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
            <div class="card-body pt-2">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>

</div>
@endsection
