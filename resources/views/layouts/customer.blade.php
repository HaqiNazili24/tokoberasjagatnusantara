@extends('layouts.base')
@section('body')

{{-- ============================================================
     CUSTOMER SIDEBAR (Desktop fixed + Mobile drawer)
     ============================================================ --}}
<div id="csApp">

    {{-- Mobile Backdrop --}}
    <div class="cs-backdrop" id="csBackdrop" onclick="csClose()"></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="cs-sidebar" id="csSidebar">

        {{-- Brand --}}
        <div class="cs-sidebar-brand">
            <div class="cs-sidebar-brand-icon">
                <img src="/assets/images/logo.png" alt="{{ config('app.store.name') }}" class="cs-logo">
            </div>
            <div class="cs-sidebar-brand-text">
                <div class="cs-sidebar-brand-name">{{ config('app.store.name') }}</div>
                <div class="cs-sidebar-brand-sub">Toko Beras Terpercaya</div>
            </div>
            <button class="cs-sidebar-close d-lg-none ms-auto" onclick="csClose()" aria-label="Tutup menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Navigation --}}
        @php $cr = request()->route()->getName(); @endphp
        <nav class="cs-nav">
            {{-- MENU --}}
            <div class="cs-nav-section-label">MENU</div>
            <a href="{{ route('home') }}" class="cs-nav-link {{ $cr === 'home' && !request('category') && !request('sub_category') ? 'active' : '' }}" data-tooltip="Dashboard">
                <span class="cs-nav-icon"><i class="bi bi-house-fill"></i></span>
                <span class="cs-nav-text">Dashboard</span>
            </a>

            {{-- KATALOG --}}
            <div class="cs-nav-divider"></div>
            <div class="cs-nav-section-label">KATALOG</div>

            <a href="{{ route('home') }}" class="cs-nav-link {{ $cr === 'home' && !request('category') && !request('sub_category') ? 'active' : '' }}" data-tooltip="Semua Produk">
                <span class="cs-nav-icon"><i class="bi bi-grid-fill"></i></span>
                <span class="cs-nav-text">Semua Produk</span>
            </a>

            @auth
            <div class="cs-nav-divider"></div>
            <div class="cs-nav-section-label">PESANAN</div>

            <a href="{{ route('orders.index') }}" class="cs-nav-link {{ str_starts_with($cr, 'orders') ? 'active' : '' }}" data-tooltip="Pesanan Saya">
                <span class="cs-nav-icon"><i class="bi bi-bag-check"></i></span>
                <span class="cs-nav-text">Pesanan Saya</span>
            </a>
            @endauth
        </nav>

        {{-- Bottom: User / Logout --}}
        <div class="cs-sidebar-footer">
            @if(auth()->check())
                @if(auth()->user()->isOwner())
                <div class="mb-2 px-2">
                    <a href="{{ route('owner.dashboard') }}" class="btn w-100 d-flex align-items-center justify-content-center gap-2 cs-admin-link" style="background:rgba(0,117,74,0.15); border: 1px solid #00754A; color:#00754A; border-radius:8px; font-weight:600; font-size:14px; padding:8px;" data-tooltip="Panel Owner">
                        <i class="bi bi-speedometer2"></i> <span class="cs-nav-text">Panel Owner</span>
                    </a>
                </div>
                @elseif(auth()->user()->isKaryawan())
                <div class="mb-2 px-2">
                    <a href="{{ route('karyawan.dashboard') }}" class="btn w-100 d-flex align-items-center justify-content-center gap-2 cs-admin-link" style="background:rgba(0,117,74,0.15); border: 1px solid #00754A; color:#00754A; border-radius:8px; font-weight:600; font-size:14px; padding:8px;" data-tooltip="Panel Karyawan">
                        <i class="bi bi-speedometer2"></i> <span class="cs-nav-text">Panel Karyawan</span>
                    </a>
                </div>
                @elseif(auth()->user()->isKurir())
                <div class="mb-2 px-2">
                    <a href="{{ route('kurir.dashboard') }}" class="btn w-100 d-flex align-items-center justify-content-center gap-2 cs-admin-link" style="background:rgba(203,162,88,0.15); border: 1px solid #cba258; color:#cba258; border-radius:8px; font-weight:600; font-size:14px; padding:8px;" data-tooltip="Panel Kurir">
                        <i class="bi bi-bicycle"></i> <span class="cs-nav-text">Panel Kurir</span>
                    </a>
                </div>
                @endif
            @endif

            @auth
            <form action="{{ route('logout') }}" method="POST" class="px-2">
                @csrf
                <button type="submit" class="cs-nav-link w-100 border-0 bg-transparent text-start" style="padding-left:10px;" data-tooltip="Logout">
                    <span class="cs-nav-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="cs-nav-text">Logout</span>
                </button>
            </form>
            @endauth

            @guest
            <div class="d-flex flex-column px-2">
                <a href="{{ route('login') }}" class="cs-nav-link w-100 text-decoration-none" data-tooltip="Login">
                    <span class="cs-nav-icon"><i class="bi bi-box-arrow-in-right"></i></span>
                    <span class="cs-nav-text">Login</span>
                </a>
                <a href="{{ route('register') }}" class="cs-nav-link w-100 text-decoration-none" data-tooltip="Daftar">
                    <span class="cs-nav-icon"><i class="bi bi-person-plus"></i></span>
                    <span class="cs-nav-text">Daftar</span>
                </a>
            </div>
            @endguest
        </div>
    </aside>

    {{-- ===== MAIN CONTENT AREA ===== --}}
    <div class="cs-main">

        {{-- Mobile Header Wrapper (Sticky) --}}
        <div class="cs-mobile-header-wrapper d-lg-none">
            {{-- Mobile Topbar --}}
            <header class="cs-topbar">
                <button class="cs-topbar-burger" id="csBurger" onclick="csOpen()" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>
                <a class="cs-topbar-brand" href="{{ route('home') }}">
                    <img src="/assets/images/logo.png" alt="{{ config('app.store.name') }}" class="cs-topbar-logo">
                    <span>{{ config('app.store.name') }}</span>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <button class="cs-topbar-search-toggle" onclick="csMobileSearchToggle()" aria-label="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                    @auth
                    <a href="{{ route('cart.index') }}" class="cs-topbar-icon position-relative">
                        <i class="bi bi-cart3"></i>
                        @if(($globalCartCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">{{ $globalCartCount }}</span>
                        @endif
                    </a>
                    @endauth
                    @guest
                    <a href="{{ route('login') }}" class="cs-topbar-icon"><i class="bi bi-person"></i></a>
                    @endguest
                </div>
            </header>

            {{-- Mobile Search (collapsible) --}}
            <div class="cs-mobile-search" id="csMobileSearch">
                <form action="{{ route('search') }}" class="cs-mobile-search-form" method="GET">
                    <i class="bi bi-search" style="color:rgba(0,0,0,0.4); font-size:14px; flex-shrink:0;"></i>
                    <input class="cs-mobile-search-input" type="search" name="q" placeholder="Cari produk beras, minyak goreng…" value="{{ request('q') }}">
                    <button class="cs-mobile-search-btn" type="submit"><i class="bi bi-arrow-right"></i></button>
                </form>
            </div>
        </div>

        {{-- Desktop Top Search Bar --}}
        <div class="cs-search-bar d-none d-lg-flex">
            <button class="cs-topbar-action me-2" onclick="csToggle()" aria-label="Toggle sidebar" id="csToggleBtn">
                <i class="bi bi-chevron-left"></i>
            </button>
            <form action="{{ route('search') }}" class="cs-search-form" method="GET">
                <i class="bi bi-search cs-search-icon"></i>
                <input class="cs-search-input" type="search" name="q" placeholder="Cari produk beras, minyak goreng…" value="{{ request('q') }}">
                <button class="btn btn-tb-primary cs-search-btn" type="submit">Cari</button>
            </form>
            @auth
            <a href="{{ route('cart.index') }}" class="cs-search-cart position-relative">
                <i class="bi bi-cart3"></i>
                @if(($globalCartCount ?? 0) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.65rem; padding:.3em .5em;">{{ $globalCartCount }}</span>
                @endif
            </a>
            <div class="cs-search-user">
                <div class="cs-search-avatar">{{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}</div>
                <span>{{ explode(' ', auth()->user()->full_name)[0] }}</span>
            </div>
            @endauth
            @guest
            <a href="{{ route('login') }}" class="btn btn-tb-outline btn-sm">Login</a>
            <a href="{{ route('register') }}" class="btn btn-tb-primary btn-sm">Daftar</a>
            @endguest
        </div>

        {{-- Page Content --}}
        <main class="cs-content">
            @include('partials.alerts')
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="cs-footer">
            <div class="cs-footer-inner">
                <strong>{{ config('app.store.name') }}</strong>
                <span class="cs-footer-dot">&middot;</span>
                <span>Beras berkualitas untuk keluarga Indonesia</span>
            </div>
            <small>&copy; {{ date('Y') }} {{ config('app.store.name') }}</small>
        </footer>
    </div>

    @push('scripts')
    <script>
        function csOpen()  {
            document.getElementById('csSidebar').classList.add('is-open');
            document.getElementById('csBackdrop').classList.add('is-visible');
            document.body.classList.add('cs-no-scroll');
        }
    function csClose() {
        document.getElementById('csSidebar').classList.remove('is-open');
        document.getElementById('csBackdrop').classList.remove('is-visible');
        document.body.classList.remove('cs-no-scroll');
    }
    function csMobileSearchToggle() {
        var el = document.getElementById('csMobileSearch');
        var btn = document.querySelector('.cs-topbar-search-toggle');
        if (el) {
            el.classList.toggle('is-open');
            if (btn) btn.classList.toggle('is-active', el.classList.contains('is-open'));
            if (el.classList.contains('is-open')) {
                var input = el.querySelector('.cs-mobile-search-input');
                if (input) input.focus();
            }
        }
    }
    function csToggle() {
        const app = document.getElementById('csApp');
        const icon = document.querySelector('#csToggleBtn i');
        if(!icon) return;
        
        app.classList.toggle('sidebar-collapsed');
        
        if (app.classList.contains('sidebar-collapsed')) {
            icon.className = 'bi bi-chevron-right';
        } else {
            icon.className = 'bi bi-chevron-left';
        }
        
        localStorage.setItem('sidebarCollapsed', app.classList.contains('sidebar-collapsed'));
    }
    
    // Restore state dari localStorage dan tangani responsivitas Tablet
    document.addEventListener('DOMContentLoaded', function() {
        const app = document.getElementById('csApp');
        const icon = document.querySelector('#csToggleBtn i');
        const isTablet = window.innerWidth >= 992 && window.innerWidth < 1200;
        const savedState = localStorage.getItem('sidebarCollapsed');
        
        // Jika disave tertutup, ATAU jika belum disave tapi layarnya tablet -> auto collapse
        if (savedState === 'true' || (savedState === null && isTablet)) {
            app.classList.add('sidebar-collapsed');
            if(icon) icon.className = 'bi bi-chevron-right';
        }
        
        // Listener untuk resize window jika pengguna mengecilkan layar dari desktop ke tablet
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992 && window.innerWidth < 1200) {
                // Hanya auto-collapse jika user belum secara eksplisit menyimpan state terbuka
                if (localStorage.getItem('sidebarCollapsed') !== 'false' && !app.classList.contains('sidebar-collapsed')) {
                    app.classList.add('sidebar-collapsed');
                    if(icon) icon.className = 'bi bi-chevron-right';
                }
            } else if (window.innerWidth >= 1200) {
                // Di desktop, kembalikan ke state tersimpan atau terbuka default
                if (localStorage.getItem('sidebarCollapsed') !== 'true' && app.classList.contains('sidebar-collapsed')) {
                    app.classList.remove('sidebar-collapsed');
                    if(icon) icon.className = 'bi bi-chevron-left';
                }
            }
            // Tutup mobile search saat resize ke desktop
            if (window.innerWidth >= 992) {
                var mobileSearch = document.getElementById('csMobileSearch');
                if (mobileSearch) mobileSearch.classList.remove('is-open');
            }
        });
    });
</script>

{{-- WhatsApp Bubble Component (Customer Only) --}}
<WhatsAppBubble 
    nomor-w-a="6285311696756" 
    pesan-default="Halo, saya ingin bertanya mengenai produk Toko Beras Jagat Nusantara."
/>

{{-- Fallback: Vanilla JS WhatsApp Bubble --}}
<script>
    window.addEventListener('load', function() {
        // Check if Vue bubble rendered
        setTimeout(() => {
            if (!document.querySelector('.wa-bubble')) {
                createFallbackWABubble();
            }
        }, 2000);
    });

    function createFallbackWABubble() {
        const pesan = encodeURIComponent('Halo, saya ingin bertanya mengenai produk Toko Beras Jagat Nusantara.');
        const bubbleHTML = `
            <a href="https://wa.me/6285311696756?text=${pesan}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="wa-bubble" 
               title="Chat via WhatsApp"
               style="position: fixed; bottom: 16px; right: 16px; width: 56px; height: 56px; border-radius: 50%; background-color: #25D366; color: #fff; display: flex; align-items: center; justify-content: center; z-index: 9999; font-size: 28px; border: none; text-decoration: none; box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.4); animation: wa-pulse 2s infinite; cursor: pointer;">
                <i class="bi bi-whatsapp"></i>
            </a>
        `;
        document.body.insertAdjacentHTML('beforeend', bubbleHTML);
    }
</script>
@endpush
@endsection
