@extends('layouts.base')
@section('body')
<div id="adminApp" class="d-flex flex-column min-vh-100" style="background: #F2F0EB;">

    {{-- Topbar --}}
    <header class="navbar navbar-expand-lg navbar-dark sticky-top px-3 py-3" style="background-color: #1E3932; border-bottom: 3px solid #cba258;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand fw-bold d-flex align-items-center gap-2" style="color: white; font-size: 19px;">
                <i class="bi bi-bicycle" style="color: #cba258;"></i> Kurir Panel
            </span>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white-50 small d-none d-md-inline">{{ auth()->user()->full_name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size: 12px; font-weight: 600;">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <main class="container py-4 flex-grow-1">
        @include('partials.alerts')
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center py-3 border-top mt-auto" style="background-color: white; border-color: #edebe9 !important;">
        <div class="container">
            <span class="text-muted small">&copy; {{ date('Y') }} {{ config('app.store.name') }} &middot; Kurir Delivery Portal</span>
        </div>
    </footer>
</div>
@endsection
