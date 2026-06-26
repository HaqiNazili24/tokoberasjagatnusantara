@extends('layouts.base')
@section('title','Login')
@section('body')
<div class="d-flex align-items-center min-vh-100" style="background-color: var(--color-canvas-warm);">
    <div class="container">
        <div class="row justify-content-center py-5">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                    <div class="text-center mb-4">
                        <img src="/assets/images/logo.png" alt="Logo" height="60" class="mb-3">
                        <h4 class="fw-bold text-success">Masuk Akun</h4>
                        <p class="text-muted small">Silakan masuk untuk melanjutkan belanja beras premium</p>
                    </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="emailFloating" value="{{ old('email') }}" placeholder="name@example.com" required>
                    <label for="emailFloating">Email</label>
                </div>
                
                <div class="position-relative mb-3">
                    <div class="form-floating">
                        <input type="password" name="password" id="passwordLogin" class="form-control" placeholder="Password" style="padding-right: 48px;" required>
                        <label for="passwordLogin">Password</label>
                    </div>
                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-secondary me-2 p-1 text-decoration-none" style="border: none !important; background: transparent !important; box-shadow: none !important; transform: translateY(-50%); z-index: 5;" onclick="togglePassword('passwordLogin', this)">
                        <i class="bi bi-eye fs-5"></i>
                    </button>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="rm">
                        <label class="form-check-label text-muted small" for="rm">Ingat saya</label>
                    </div>
                </div>
                
                <button class="btn btn-tb-primary w-100 py-2 fs-6">Masuk</button>
            </form>
            
            <p class="text-center mt-4 mb-0 small text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-success fw-semibold text-decoration-none">Daftar Sekarang</a></p>
        </div>
    </div>
</div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endpush
@endsection