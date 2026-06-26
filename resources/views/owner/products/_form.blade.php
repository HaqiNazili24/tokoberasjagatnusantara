@php $isEdit = isset($product); @endphp
<div class="row g-4">

    {{-- LEFT: Main Fields --}}
    <div class="col-md-8">

        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color: #1E3932;"><i class="bi bi-info-circle me-2" style="color: #00754A;"></i>Informasi Produk</h5>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: rgba(0,0,0,0.87);">Nama Produk Beras <span class="text-danger">*</span></label>
                    <input name="name" class="form-control" style="border-radius: 8px;" placeholder="Contoh: Beras Rojo Lele Premium"
                           value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: rgba(0,0,0,0.87);">Deskripsi Produk</label>
                    <textarea name="description" rows="5" class="form-control" style="border-radius: 8px;"
                              placeholder="Deskripsikan kualitas beras, pulen, wangi, dsb…">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: rgba(0,0,0,0.87);">Harga (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">Rp</span>
                            <input type="number" name="price" class="form-control" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;"
                                   placeholder="0"
                                   value="{{ old('price', isset($product) ? (int) $product->price : '') }}"
                                   required min="0" step="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: rgba(0,0,0,0.87);">Kemasan <span class="text-danger">*</span></label>
                        <input name="weight_label" class="form-control" style="border-radius: 8px;" placeholder="Contoh: 5kg, 10kg, 25kg"
                               value="{{ old('weight_label', $product->weight_label ?? '5kg') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: rgba(0,0,0,0.87);">Stok Real-time <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" style="border-radius: 8px;" placeholder="0"
                               value="{{ old('stock', $product->stock ?? 0) }}" required min="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Photo Upload --}}
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3" style="color: #1E3932;"><i class="bi bi-image me-2" style="color: #00754A;"></i>Foto Produk</h5>

                <div class="mb-3">
                    <label class="form-label text-muted small">Pilih file foto beras (Format JPG, PNG — Maksimal 2MB)</label>
                    <input type="file" name="image" accept="image/jpg,image/jpeg,image/png" class="form-control" style="border-radius: 8px;">
                </div>

                @if($isEdit && $product->image_url)
                <div class="mt-3 p-3 rounded-3 d-flex align-items-center gap-3" style="background-color: #f2f0eb;">
                    <img src="{{ asset('storage/'.$product->image_url) }}"
                         class="rounded-3 shadow-sm"
                         style="width:72px; height:72px; object-fit:cover;"
                         onerror="this.src='https://placehold.co/72x72/e8f5e9/2D5016?text=Beras'"
                         alt="Foto produk saat ini">
                    <div>
                        <p class="mb-0 fw-bold small" style="color: #1E3932;">Foto produk saat ini</p>
                        <small class="text-muted">Upload foto baru untuk mengganti foto lama.</small>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: Sidebar Options --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 90px; border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color: #1E3932;"><i class="bi bi-sliders me-2" style="color: #00754A;"></i>Pengaturan</h5>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: rgba(0,0,0,0.87);">Sub Kategori Beras <span class="text-danger">*</span></label>
                    <select name="sub_category_id" class="form-select" style="border-radius: 8px;" required>
                        <option value="">— Pilih Sub Kategori —</option>
                        @foreach($subCategories as $sc)
                            <option value="{{ $sc->id }}" @selected(old('sub_category_id', $product->sub_category_id ?? '') == $sc->id)>
                                {{ $sc->category->name }} / {{ $sc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold d-block mb-2" style="color: rgba(0,0,0,0.87);">Visibilitas Toko</label>
                    <div class="form-check form-switch mb-0">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="ia"
                               @checked(old('is_active', $isEdit ? $product->is_active : true))>
                        <label class="form-check-label fw-bold" for="ia">Aktif (Tampil di Katalog)</label>
                    </div>
                    <small class="text-muted d-block mt-1">Nonaktifkan untuk menyembunyikan beras ini.</small>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn" style="background-color: #00754A; color: white; border-radius: 50px; padding: 12px; font-weight: 600; border: none; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                        <i class="bi bi-check-lg me-2"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Buat Produk' }}
                    </button>
                    <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary" style="border-radius: 50px; padding: 12px; font-weight: 600;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
