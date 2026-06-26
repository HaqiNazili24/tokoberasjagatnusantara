@extends('layouts.owner')
@section('page-title','Kelola Akun')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #006241;">Kelola Akun Tim Operasional</h4>
        <p class="text-muted mb-0">Kelola kredensial login dan peran untuk Karyawan dan Kurir.</p>
    </div>
    <a href="{{ route('owner.users.create') }}" class="btn text-white" style="background-color: #00754A; border-radius: 50px; padding: 10px 24px; font-weight: 600; border: none; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Akun Baru
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead style="background-color: #edebe9; color: #1E3932;">
                <tr>
                    <th style="padding: 16px;" class="ps-4">Nama Lengkap</th>
                    <th style="padding: 16px;">Email</th>
                    <th style="padding: 16px;">Nomor Telepon</th>
                    <th style="padding: 16px;">Role / Peran</th>
                    <th style="padding: 16px;">Tanggal Dibuat</th>
                    <th style="padding: 16px;" class="pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #edebe9;">
                    <td class="ps-4" style="padding: 12px 16px;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle" style="width: 40px; height: 40px; background-color: {{ $user->role === 'karyawan' ? '#00754A' : '#cba258' }}">
                                {{ strtoupper(substr($user->full_name, 0, 1)) }}
                            </div>
                            <span class="fw-bold" style="color: #1E3932;">{{ $user->full_name }}</span>
                        </div>
                    </td>
                    <td style="padding: 12px 16px;">{{ $user->email }}</td>
                    <td style="padding: 12px 16px;">{{ $user->phone }}</td>
                    <td style="padding: 12px 16px;">
                        @if($user->role === 'karyawan')
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #d4e9e2; color: #006241; font-weight: 600;">Karyawan Toko</span>
                        @elseif($user->role === 'kurir')
                            <span class="badge rounded-pill px-3 py-2 text-dark" style="background-color: #faf6ee; border: 1px solid #dfc49d; font-weight: 600;">Kurir Pengiriman</span>
                        @endif
                    </td>
                    <td class="text-muted" style="padding: 12px 16px;">{{ $user->created_at->format('d M Y H:i') }}</td>
                    <td class="pe-4" style="padding: 12px 16px;">
                        <div class="d-flex gap-2">
                            <a href="{{ route('owner.users.edit', $user) }}" class="btn btn-sm btn-outline-success" style="border-radius: 50px; padding: 6px 16px;" title="Edit">
                                <i class="bi bi-pencil-fill me-1"></i> Edit
                            </a>
                            <form action="{{ route('owner.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->full_name }}?')">
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
                    <td colspan="6" class="text-center py-5 text-muted" style="background-color: white;">
                        <i class="bi bi-people fs-1 d-block mb-3 opacity-50" style="color: #006241;"></i>
                        Belum ada karyawan atau kurir terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
