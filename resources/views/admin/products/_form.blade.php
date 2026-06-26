@php $isEdit = isset($product); @endphp
<div class="row g-3">

    {{-- LEFT: Main Fields --}}
    <div class="col-md-8">

        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-tb-green"></i>Informasi Produk</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input name="name" class="form-control" placeholder="Contoh: Beras Premium Pulen"
                           value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi Produk</label>
                    <textarea name="description" rows="4" class="form-control"
                              placeholder="Deskripsikan produk secara singkat…">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted">Rp</span>
                            <input type="number" name="price" class="form-control"
                                   placeholder="0"
                                   value="{{ old('price', isset($product) ? (int) $product->price : '') }}"
                                   required min="0" step="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kemasan <span class="text-danger">*</span></label>
                        <input name="weight_label" class="form-control" placeholder="Contoh: 5kg, 25kg"
                               value="{{ old('weight_label', $product->weight_label ?? '5kg') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" placeholder="0"
                               value="{{ old('stock', $product->stock ?? 0) }}" required min="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Photo Upload --}}
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-image me-2 text-tb-green"></i>Foto Produk</h6>

                <div class="admin-upload-area mb-2">
                    <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-2"></i>
                    <p class="mb-1 fw-semibold">Klik atau seret file ke sini</p>
                    <small class="text-muted">Format JPG, PNG — Maksimal 2MB</small>
                    <input type="file" name="image" accept="image/jpg,image/jpeg,image/png"
                           class="admin-upload-input">
                </div>

                @if($isEdit && $product->image_url)
                <div class="mt-3 p-3 rounded-3 bg-light d-flex align-items-center gap-3">
                    <img src="{{ asset('storage/'.$product->image_url) }}"
                         class="rounded-3"
                         style="width:72px; height:72px; object-fit:cover;"
                         onerror="this.src='https://placehold.co/72x72/e8f5e9/2D5016?text=Beras'"
                         alt="Foto produk saat ini">
                    <div>
                        <p class="mb-0 fw-semibold small">Foto saat ini</p>
                        <small class="text-muted">Upload foto baru untuk mengganti.</small>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: Sidebar Options --}}
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 90px;">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-sliders me-2 text-tb-green"></i>Pengaturan</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sub Kategori <span class="text-danger">*</span></label>
                    <select name="sub_category_id" class="form-select" required>
                        <option value="">— Pilih Sub Kategori —</option>
                        @foreach($subCategories as $sc)
                            <option value="{{ $sc->id }}" @selected(old('sub_category_id', $product->sub_category_id ?? '') == $sc->id)>
                                {{ $sc->category->name }} / {{ $sc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold d-block mb-2">Visibilitas Toko</label>
                    <div class="admin-toggle-box">
                        <div class="form-check form-switch mb-0">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="ia"
                                   @checked(old('is_active', $isEdit ? $product->is_active : true))>
                            <label class="form-check-label fw-semibold" for="ia">Aktif (terlihat di toko)</label>
                        </div>
                        <small class="text-muted d-block mt-1">Nonaktifkan untuk menyembunyikan produk dari katalog.</small>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-tb-primary">
                        <i class="bi bi-check-lg me-2"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Buat Produk' }}
                    </button>
                    <a href="{{ route('kepala-toko.products.index') }}" class="btn btn-tb-outline">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>