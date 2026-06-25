@extends('layouts.owner')
@section('page-title','Edit Akun Tim')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="mb-4">
            <h4 class="fw-bold mb-1" style="color: #006241;">Edit Detail Akun</h4>
            <p class="text-muted">Perbarui data kredensial login atau peran untuk {{ $user->full_name }}.</p>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <form action="{{ route('owner.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" style="border-radius: 8px;" value="{{ old('full_name', $user->full_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" style="border-radius: 8px;" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" style="border-radius: 8px;" value="{{ old('phone', $user->phone) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Peran / Role Akses <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" style="border-radius: 8px;" required>
                            <option value="karyawan" @selected(old('role', $user->role) == 'karyawan')>Karyawan Toko</option>
                            <option value="kurir" @selected(old('role', $user->role) == 'kurir')>Kurir Pengiriman</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Password Login (Kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" style="border-radius: 8px;" placeholder="Masukkan password baru untuk mengganti password lama">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white px-4" style="background-color: #00754A; border-radius: 50px; font-weight: 600; border: none; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                            Simpan Perubahan
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
