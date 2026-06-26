@extends('layouts.base')
@section('body')
<div id="adminApp" class="d-flex flex-column flex-lg-row min-vh-100">

    {{-- ===== SIDEBAR ===== --}}
    <div class="offcanvas-lg offcanvas-start sidebar-admin p-0 flex-shrink-0" tabindex="-1" id="adminSidebar" style="width: 260px; background-color: #1E3932; color: white;" aria-labelledby="adminSidebarLabel">

        {{-- Brand / Logo --}}
        <div class="sidebar-brand px-4 py-4 d-flex align-items-center justify-content-between" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <div class="d-flex align-items-center gap-2">
                <div class="sidebar-brand-icon" style="color: #d4e9e2; font-size: 24px;">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <div>
                    <div class="sidebar-brand-name fw-bold" id="adminSidebarLabel" style="color: #ffffff; font-size: 18px;">Karyawan Panel</div>
                    <div class="sidebar-brand-sub" style="color: rgba(255,255,255,0.7); font-size: 12px;">{{ config('app.store.name') }}</div>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close"></button>
            <button type="button" class="admin-sidebar-toggle d-none d-lg-flex border-0 bg-transparent text-white ms-auto" onclick="adminToggle()" id="adminToggleBtn" aria-label="Toggle sidebar">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>

        {{-- Navigation --}}
        @php $r = request()->route()->getName(); @endphp
        <nav class="sidebar-nav px-3 pb-3 flex-grow-1 mt-4">
            <div class="sidebar-section-label" style="color: rgba(255,255,255,0.4); font-size: 11px; font-weight: 700; letter-spacing: 1px; margin-bottom: 10px; padding-left: 10px;">OPERASIONAL</div>

            <a href="{{ route('karyawan.dashboard') }}" class="sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded mb-2 text-decoration-none {{ $r==='karyawan.dashboard' ? 'active' : '' }}" style="color: white; transition: all 0.2s;" data-tooltip="Dashboard">
                <span class="sidebar-link-icon"><i class="bi bi-speedometer2"></i></span>
                <span class="sidebar-link-text">Dashboard & Stok</span>
            </a>

            <div class="sidebar-divider my-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);"></div>
            <div class="sidebar-section-label" style="color: rgba(255,255,255,0.4); font-size: 11px; font-weight: 700; letter-spacing: 1px; margin-bottom: 10px; padding-left: 10px;">AKUN</div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded mb-2 text-decoration-none w-100 border-0 bg-transparent text-start text-white" data-tooltip="Logout">
                    <span class="sidebar-link-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="sidebar-link-text">Logout</span>
                </button>
            </form>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer px-4 py-3" style="background-color: rgba(0, 0, 0, 0.2);">
            <div class="d-flex align-items-center gap-2">
                <div class="sidebar-avatar d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 36px; height: 36px; border-radius: 50%; background-color: #d4e9e2;">{{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}</div>
                <div>
                    <div class="sidebar-user-name fw-semibold" style="font-size: 14px;">{{ auth()->user()->full_name }}</div>
                    <div class="sidebar-user-role" style="font-size: 11px; color: #d4e9e2;">Karyawan Toko</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-grow-1 w-100 admin-main" style="min-width: 0; background: #F2F0EB;">

        {{-- Topbar --}}
        <header class="admin-topbar px-4 py-0 d-flex justify-content-between align-items-center sticky-top" style="height: 70px; background-color: white; border-bottom: 1px solid #edebe9; z-index: 1020;">
            <div class="d-flex align-items-center gap-3">
                <button class="btn admin-topbar-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <button class="btn admin-topbar-toggle d-none d-lg-flex" type="button" onclick="adminToggle()" aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: #006241;">@yield('page-title', 'Karyawan Dashboard')</h5>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="admin-topbar-date d-none d-md-block" style="color: rgba(0,0,0,0.58); font-size: 14px;">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <div class="admin-topbar-user d-flex align-items-center gap-2">
                    <div class="topbar-avatar d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; border-radius: 50%; background-color: #00754A;">{{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}</div>
                    <span class="d-none d-md-block fw-semibold" style="color: rgba(0,0,0,0.87); font-size: 14px;">{{ auth()->user()->full_name }}</span>
                </div>
            </div>
        </header>

        <main class="admin-content p-3 p-lg-4">
            @include('partials.alerts')
            @yield('content')
        </main>
    </div>
</div>

<style>
    .sidebar-link:hover, .sidebar-link.active {
        background-color: #00754A !important;
        color: white !important;
    }
    .admin-sidebar-collapsed #adminSidebar {
        width: 80px !important;
    }
    .admin-sidebar-collapsed .sidebar-link-text,
    .admin-sidebar-collapsed .sidebar-brand-name,
    .admin-sidebar-collapsed .sidebar-brand-sub,
    .admin-sidebar-collapsed .sidebar-section-label,
    .admin-sidebar-collapsed .sidebar-footer {
        display: none !important;
    }
    .admin-sidebar-collapsed .sidebar-brand {
        justify-content: center !important;
        padding: 20px 0 !important;
    }
    .admin-sidebar-collapsed .admin-sidebar-toggle {
        display: none !important;
    }
    .admin-sidebar-collapsed .sidebar-link {
        justify-content: center !important;
        padding: 10px 0 !important;
    }
    .admin-sidebar-collapsed .sidebar-link-icon {
        font-size: 20px !important;
    }
</style>

@push('scripts')
<script>
    function adminToggle() {
        var app = document.getElementById('adminApp');
        var icon = document.querySelector('#adminToggleBtn i');
        if (!app) return;

        app.classList.toggle('admin-sidebar-collapsed');

        if (icon) {
            icon.className = app.classList.contains('admin-sidebar-collapsed')
                ? 'bi bi-chevron-right'
                : 'bi bi-chevron-left';
        }

        localStorage.setItem('adminSidebarCollapsed', app.classList.contains('admin-sidebar-collapsed'));
    }

    document.addEventListener('DOMContentLoaded', function() {
        var app = document.getElementById('adminApp');
        var icon = document.querySelector('#adminToggleBtn i');
        if (!app) return;

        if (window.innerWidth >= 992 && localStorage.getItem('adminSidebarCollapsed') === 'true') {
            app.classList.add('admin-sidebar-collapsed');
            if (icon) icon.className = 'bi bi-chevron-right';
        }
    });
</script>
@endpush
@endsection
