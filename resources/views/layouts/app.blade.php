<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Yobbal') }} — @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700;900&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            :root {
                --bg-deep:       #060B18;
                --bg-card:       #0D1629;
                --bg-card-hover: #111e38;
                --bg-glass:      rgba(13, 22, 41, 0.85);
                --sidebar-w:     240px;
                --accent:        #F5A623;
                --accent-dim:    rgba(245, 166, 35, 0.12);
                --accent-glow:   rgba(245, 166, 35, 0.25);
                --blue:          #2563EB;
                --blue-light:    #60A5FA;
                --green:         #22C55E;
                --text:          #EEF2FF;
                --text-muted:    #8892B0;
                --text-dim:      #4B5679;
                --border:        rgba(255,255,255,0.07);
                --border-hover:  rgba(255,255,255,0.13);
                --radius:        14px;
                --font-head:     'Unbounded', sans-serif;
                --font-body:     'Outfit', sans-serif;
            }

            html { scroll-behavior: smooth; }

            body {
                background-color: var(--bg-deep);
                color: var(--text);
                font-family: var(--font-body);
                font-size: 15px;
                line-height: 1.6;
                min-height: 100vh;
            }

            /* ── NOISE + GRID ── */
            body::before {
                content: '';
                position: fixed; inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
                pointer-events: none; z-index: 0; opacity: 0.3;
            }

            /* ── APP SHELL ── */
            .app-shell {
                display: flex;
                min-height: 100vh;
                position: relative;
                z-index: 1;
            }

            /* ══════════════════════════════
               SIDEBAR
            ══════════════════════════════ */
            .sidebar {
                width: var(--sidebar-w);
                background: var(--bg-card);
                border-right: 1px solid var(--border);
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0; left: 0; bottom: 0;
                z-index: 50;
                transition: transform 0.3s ease;
            }

            /* Logo */
            .sidebar-logo {
                padding: 28px 24px 24px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                gap: 10px;
                text-decoration: none;
            }

            .logo-dot {
                width: 9px; height: 9px;
                border-radius: 50%;
                background: var(--accent);
                box-shadow: 0 0 12px var(--accent);
                animation: pulseDot 2s ease-in-out infinite;
                flex-shrink: 0;
            }

            @keyframes pulseDot {
                0%, 100% { opacity: 1; transform: scale(1); }
                50%       { opacity: 0.6; transform: scale(1.4); }
            }

            .logo-text {
                font-family: var(--font-head);
                font-size: 1.15rem;
                font-weight: 700;
                color: var(--text);
                letter-spacing: -0.02em;
            }

            /* Nav */
            .sidebar-nav {
                flex: 1;
                padding: 20px 14px;
                display: flex;
                flex-direction: column;
                gap: 4px;
                overflow-y: auto;
            }

            .nav-section-label {
                font-size: 0.62rem;
                font-weight: 700;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: var(--text-dim);
                padding: 16px 10px 8px;
            }

            .nav-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 12px;
                border-radius: 10px;
                text-decoration: none;
                color: var(--text-muted);
                font-size: 0.875rem;
                font-weight: 500;
                transition: all 0.18s ease;
                position: relative;
            }

            .nav-link:hover {
                background: rgba(255,255,255,0.05);
                color: var(--text);
            }

            .nav-link.active {
                background: var(--accent-dim);
                color: var(--accent);
                border: 1px solid rgba(245,166,35,0.2);
            }

            .nav-link.active::before {
                content: '';
                position: absolute;
                left: 0; top: 20%; bottom: 20%;
                width: 3px;
                background: var(--accent);
                border-radius: 0 3px 3px 0;
            }

            .nav-icon {
                width: 18px; height: 18px;
                flex-shrink: 0;
                opacity: 0.7;
            }

            .nav-link.active .nav-icon,
            .nav-link:hover .nav-icon { opacity: 1; }

            /* Sidebar footer */
            .sidebar-footer {
                padding: 16px 14px;
                border-top: 1px solid var(--border);
            }

            .user-card {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 12px;
                border-radius: 10px;
                background: rgba(255,255,255,0.03);
                border: 1px solid var(--border);
                cursor: pointer;
                transition: all 0.2s;
                position: relative;
            }

            .user-card:hover {
                background: rgba(255,255,255,0.06);
                border-color: var(--border-hover);
            }

            .user-avatar {
                width: 34px; height: 34px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--accent), #d4811a);
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: var(--font-head);
                font-size: 0.75rem;
                font-weight: 700;
                color: #000;
                flex-shrink: 0;
            }

            .user-info { flex: 1; min-width: 0; }
            .user-name {
                font-size: 0.82rem;
                font-weight: 600;
                color: var(--text);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .user-role {
                font-size: 0.7rem;
                color: var(--text-muted);
            }

            /* Dropdown menu */
            .user-dropdown {
                position: absolute;
                bottom: calc(100% + 8px);
                left: 0; right: 0;
                background: var(--bg-card);
                border: 1px solid var(--border);
                border-radius: 10px;
                overflow: hidden;
                display: none;
                box-shadow: 0 -20px 60px rgba(0,0,0,0.5);
                z-index: 100;
            }

            .user-dropdown.open { display: block; }

            .dropdown-item {
                display: block;
                width: 100%;
                padding: 10px 14px;
                text-align: left;
                background: transparent;
                border: none;
                color: var(--text-muted);
                font-family: var(--font-body);
                font-size: 0.82rem;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.15s;
            }

            .dropdown-item:hover {
                background: rgba(255,255,255,0.05);
                color: var(--text);
            }

            .dropdown-item.danger { color: #f87171; }
            .dropdown-item.danger:hover { background: rgba(248,113,113,0.08); }

            .dropdown-divider {
                height: 1px;
                background: var(--border);
                margin: 4px 0;
            }

            /* ══════════════════════════════
               MAIN AREA
            ══════════════════════════════ */
            .main-area {
                margin-left: var(--sidebar-w);
                flex: 1;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            /* Topbar */
            .topbar {
                height: 64px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 32px;
                border-bottom: 1px solid var(--border);
                background: rgba(6,11,24,0.9);
                backdrop-filter: blur(20px);
                position: sticky;
                top: 0;
                z-index: 40;
            }

            .topbar-title {
                font-family: var(--font-head);
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--text);
                letter-spacing: -0.01em;
            }

            .topbar-right {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .topbar-btn {
                width: 36px; height: 36px;
                border-radius: 8px;
                border: 1px solid var(--border);
                background: transparent;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: var(--text-muted);
                transition: all 0.18s;
                text-decoration: none;
            }

            .topbar-btn:hover {
                border-color: var(--border-hover);
                color: var(--text);
                background: rgba(255,255,255,0.04);
            }

            .btn-primary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 9px 20px;
                border-radius: 10px;
                border: 1px solid var(--accent);
                background: var(--accent);
                color: #000;
                font-family: var(--font-head);
                font-size: 0.72rem;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                letter-spacing: 0.02em;
                transition: all 0.2s ease;
                white-space: nowrap;
            }

            .btn-primary:hover {
                background: #f0b84a;
                box-shadow: 0 0 24px var(--accent-glow);
                transform: translateY(-1px);
            }

            /* Page content */
            .page-content {
                flex: 1;
                padding: 36px 32px;
            }

            /* Flash messages */
            .flash {
                padding: 12px 18px;
                border-radius: 10px;
                margin-bottom: 24px;
                font-size: 0.875rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 10px;
                animation: fadeUp 0.4s ease;
            }

            .flash-success {
                background: rgba(34,197,94,0.1);
                border: 1px solid rgba(34,197,94,0.25);
                color: #4ade80;
            }

            .flash-error {
                background: rgba(248,113,113,0.1);
                border: 1px solid rgba(248,113,113,0.25);
                color: #f87171;
            }

            .flash-info {
                background: rgba(96,165,250,0.1);
                border: 1px solid rgba(96,165,250,0.25);
                color: var(--blue-light);
            }

            /* Footer */
            .app-footer {
                padding: 20px 32px;
                border-top: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
            }

            .footer-copy {
                font-size: 0.75rem;
                color: var(--text-dim);
            }

            .footer-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 0.7rem;
                color: var(--text-dim);
            }

            .footer-badge .live-dot {
                width: 6px; height: 6px;
                border-radius: 50%;
                background: var(--green);
                animation: pulseDot 2s ease-in-out infinite;
            }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(10px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ── MOBILE ── */
            .mobile-toggle {
                display: none;
                width: 36px; height: 36px;
                border-radius: 8px;
                border: 1px solid var(--border);
                background: transparent;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: var(--text-muted);
                flex-shrink: 0;
            }

            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                }
                .sidebar.open {
                    transform: translateX(0);
                    box-shadow: 0 0 80px rgba(0,0,0,0.8);
                }
                .main-area {
                    margin-left: 0;
                }
                .topbar { padding: 0 16px; }
                .page-content { padding: 24px 16px; }
                .mobile-toggle { display: flex; }
                .app-footer { padding: 16px; }
            }

            /* ── SCROLLBAR ── */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--border-hover); }
        </style>

        @stack('styles')
    </head>

    <body>
        <div class="app-shell">

            <!-- ══ SIDEBAR ══ -->
            <aside class="sidebar" id="sidebar">

                <a href="{{ route('home') }}" class="sidebar-logo">
                    <span class="logo-dot"></span>
                    <span class="logo-text">Yobbal</span>
                </a>

                <nav class="sidebar-nav">
                    <span class="nav-section-label">Principal</span>

                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                        Tableau de bord
                    </a>

                    <a href="{{ route('colis.index') }}" class="nav-link {{ request()->routeIs('colis.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            <path d="M16 3H8L6 7h12l-2-4z"/>
                            <path d="M12 12v4m-2-2h4"/>
                        </svg>
                        Mes Colis
                    </a>

                    <span class="nav-section-label">Compte</span>

                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4m0-4h.01"/>
                        </svg>
                        À propos
                    </a>

                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        </svg>
                        Profil
                    </a>
                </nav>

                <!-- User card + dropdown -->
                <div class="sidebar-footer">
                    <div class="user-card" id="userCard" onclick="toggleUserDropdown()">
                        <div class="user-avatar" id="userInitials">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">Utilisateur</div>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--text-dim); flex-shrink:0;">
                            <path d="M6 9l6-6 6 6"/>
                        </svg>

                        <!-- Dropdown -->
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                ⚙️ &nbsp;Mon profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item danger">
                                    → &nbsp;Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- ══ MAIN ══ -->
            <div class="main-area">

                <!-- Topbar -->
                <header class="topbar">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <button class="mobile-toggle" id="mobileToggle" onclick="toggleSidebar()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12h18M3 6h18M3 18h18"/>
                            </svg>
                        </button>
                        <span class="topbar-title">@yield('title', 'Dashboard')</span>
                    </div>
                    <div class="topbar-right">
                        <!-- Notif bell -->
                        <button class="topbar-btn" title="Notifications">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
                            </svg>
                        </button>
                        <a href="{{ route('colis.create') }}" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Nouveau colis
                        </a>
                    </div>
                </header>

                <!-- Flash messages -->
                <div style="padding: 0 32px; padding-top: 16px;">
                    @if (session('success'))
                        <div class="flash flash-success">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="flash flash-error">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="flash flash-info">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>
                            {{ session('info') }}
                        </div>
                    @endif
                </div>

                <!-- Page content -->
                <main class="page-content">
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="app-footer">
                    <span class="footer-copy">© {{ date('Y') }} Yobbal Logistics — Livraison partout au Cameroun.</span>
                    <span class="footer-badge">
                        <span class="live-dot"></span>
                        Système opérationnel
                    </span>
                </footer>
            </div>

        </div><!-- /app-shell -->

        <!-- Mobile overlay -->
        <div id="sidebarOverlay"
             onclick="toggleSidebar()"
             style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:49; backdrop-filter:blur(4px);">
        </div>

        <script>
            // Sidebar mobile
            function toggleSidebar() {
                const sb = document.getElementById('sidebar');
                const ov = document.getElementById('sidebarOverlay');
                const isOpen = sb.classList.toggle('open');
                ov.style.display = isOpen ? 'block' : 'none';
            }

            // User dropdown
            function toggleUserDropdown() {
                document.getElementById('userDropdown').classList.toggle('open');
            }

            // Close dropdown on outside click
            document.addEventListener('click', function(e) {
                const card = document.getElementById('userCard');
                const dropdown = document.getElementById('userDropdown');
                if (card && !card.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });

            // Auto-dismiss flash messages
            setTimeout(() => {
                document.querySelectorAll('.flash').forEach(el => {
                    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-8px)';
                    setTimeout(() => el.remove(), 400);
                });
            }, 4000);
        </script>

        @stack('scripts')
    </body>
</html>
