@extends('layouts.owner')
@section('page-title','Tambah Akun Tim')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-4">
            <h4 class="fw-bold mb-1" style="color: #006241;">Tambah Akun Karyawan / Kurir</h4>
            <p class="text-muted">Buat akun login baru dengan menentukan role operasional mereka.</p>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <form action="{{ route('owner.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" style="border-radius: 8px;" placeholder="Contoh: Andi Wijaya" value="{{ old('full_name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" style="border-radius: 8px;" placeholder="Contoh: andi@example.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" style="border-radius: 8px;" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Peran / Role Akses <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" style="border-radius: 8px;" required>
                            <option value="">-- Pilih Peran --</option>
                            <option value="karyawan" @selected(old('role') == 'karyawan')>Karyawan Toko</option>
                            <option value="kurir" @selected(old('role') == 'kurir')>Kurir Pengiriman</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Password Login <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" style="border-radius: 8px;" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white px-4" style="background-color: #00754A; border-radius: 50px; font-weight: 600; border: none; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            Buat Akun Baru
                        </button>
                        <a href="{{ route('owner.users.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 50px; font-weight: 600;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
