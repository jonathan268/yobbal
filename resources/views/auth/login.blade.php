<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion — {{ config('app.name', 'Yobbal') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700;900&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-deep:    #060B18;
            --bg-card:    #0D1629;
            --bg-glass:   rgba(13,22,41,0.9);
            --accent:     #F5A623;
            --accent-dim: rgba(245,166,35,0.12);
            --accent-glow:rgba(245,166,35,0.28);
            --text:       #EEF2FF;
            --text-muted: #8892B0;
            --text-dim:   #4B5679;
            --border:     rgba(255,255,255,0.07);
            --border-h:   rgba(255,255,255,0.14);
            --radius:     14px;
            --font-head:  'Unbounded', sans-serif;
            --font-body:  'Outfit', sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg-deep);
            color: var(--text);
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Noise */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: 0.3;
        }

        /* Grid */
        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(37,99,235,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37,99,235,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none; z-index: 0;
        }

        /* Orbs */
        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(130px); pointer-events: none; z-index: 0;
        }
        .orb-1 { width: 500px; height: 500px; background: rgba(245,166,35,0.08); top: -180px; right: -80px; animation: floatOrb 14s ease-in-out infinite; }
        .orb-2 { width: 400px; height: 400px; background: rgba(37,99,235,0.09); bottom: -100px; left: -100px; animation: floatOrb 18s ease-in-out infinite reverse; }

        @keyframes floatOrb {
            0%,100% { transform: translate(0,0); }
            50%      { transform: translate(20px,-30px); }
        }

        /* ── LAYOUT ── */
        .page {
            position: relative; z-index: 1;
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        @media (max-width: 860px) {
            .page { grid-template-columns: 1fr; }
            .left-panel { display: none; }
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            background: linear-gradient(135deg, rgba(245,166,35,0.06) 0%, rgba(37,99,235,0.06) 100%);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
        }

        .panel-logo {
            display: flex; align-items: center; gap: 10px;
            font-family: var(--font-head); font-size: 1.2rem; font-weight: 700;
            color: var(--text); text-decoration: none;
        }

        .logo-dot {
            width: 9px; height: 9px; border-radius: 50%;
            background: var(--accent); box-shadow: 0 0 14px var(--accent);
            animation: pDot 2s ease-in-out infinite;
        }

        @keyframes pDot {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:.5; transform:scale(1.5); }
        }

        .panel-content { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 40px 0; }

        .panel-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 5px 14px; border-radius: 999px;
            border: 1px solid rgba(245,166,35,0.3);
            background: rgba(245,166,35,0.07);
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--accent); margin-bottom: 24px;
            width: fit-content;
        }

        .panel-title {
            font-family: var(--font-head);
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 800; letter-spacing: -0.03em;
            line-height: 1.1; margin-bottom: 16px;
        }

        .panel-title .hl { color: var(--accent); }

        .panel-sub {
            font-size: 0.95rem; color: var(--text-muted);
            line-height: 1.7; max-width: 380px; margin-bottom: 40px;
        }

        .panel-features { display: flex; flex-direction: column; gap: 14px; }

        .feat-item {
            display: flex; align-items: center; gap: 12px;
            font-size: 0.85rem; color: var(--text-muted);
        }

        .feat-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(245,166,35,0.08);
            border: 1px solid rgba(245,166,35,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }

        .panel-footer {
            font-size: 0.72rem; color: var(--text-dim);
        }

        /* ── RIGHT PANEL (Form) ── */
        .right-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            min-height: 100vh;
        }

        @media (max-width: 480px) { .right-panel { padding: 32px 20px; } }

        .form-wrapper {
            width: 100%; max-width: 420px;
        }

        /* Nav top on mobile */
        .mobile-logo {
            display: none;
            align-items: center; gap: 10px;
            font-family: var(--font-head); font-size: 1.1rem; font-weight: 700;
            color: var(--text); text-decoration: none;
            margin-bottom: 36px;
        }

        @media (max-width: 860px) { .mobile-logo { display: flex; } }

        /* Form header */
        .form-header { margin-bottom: 32px; }

        .form-tag {
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.13em; text-transform: uppercase;
            color: var(--accent); margin-bottom: 8px;
            display: flex; align-items: center; gap: 8px;
        }

        .form-tag::before {
            content: ''; display: block;
            width: 18px; height: 2px;
            background: var(--accent); border-radius: 2px;
        }

        .form-title {
            font-family: var(--font-head);
            font-size: 1.6rem; font-weight: 700;
            letter-spacing: -0.03em; color: var(--text);
            margin-bottom: 6px;
        }

        .form-sub { font-size: 0.85rem; color: var(--text-muted); }

        /* Session status */
        .session-status {
            padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.2);
            font-size: 0.82rem; color: #4ade80;
            display: flex; align-items: center; gap: 8px;
        }

        /* Form card */
        .form-card {
            background: var(--bg-glass);
            border: 1px solid var(--border);
            border-radius: 18px; padding: 32px;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 60px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.04);
        }

        @media (max-width: 480px) { .form-card { padding: 24px 18px; } }

        /* Fields */
        .field { margin-bottom: 20px; }

        .field:last-of-type { margin-bottom: 0; }

        .field-label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: var(--text-muted); margin-bottom: 8px;
        }

        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 10px; padding: 13px 16px;
            color: var(--text); font-family: var(--font-body);
            font-size: 0.9rem; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field-input::placeholder { color: var(--text-dim); font-size: 0.85rem; }

        .field-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
            background: rgba(255,255,255,0.06);
        }

        .field-input:hover:not(:focus) {
            border-color: var(--border-h);
            background: rgba(255,255,255,0.05);
        }

        .field-input.has-error { border-color: #f87171; }
        .field-input.has-error:focus { box-shadow: 0 0 0 3px rgba(248,113,113,0.15); }

        /* Input error message */
        .field-error {
            margin-top: 6px; font-size: 0.75rem; color: #f87171;
            display: flex; align-items: center; gap: 5px;
        }

        /* Remember + forgot */
        .form-options {
            display: flex; align-items: center;
            justify-content: space-between; gap: 12px;
            margin-top: 20px; flex-wrap: wrap;
        }

        .remember-label {
            display: flex; align-items: center; gap: 9px;
            font-size: 0.82rem; color: var(--text-muted); cursor: pointer;
        }

        .remember-checkbox {
            width: 16px; height: 16px;
            border-radius: 4px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.04);
            accent-color: var(--accent);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.8rem; color: var(--text-muted);
            text-decoration: none; transition: color 0.15s;
        }

        .forgot-link:hover { color: var(--accent); }

        /* Submit button */
        .btn-submit {
            width: 100%; margin-top: 24px;
            padding: 14px;
            border-radius: 11px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #000;
            font-family: var(--font-head);
            font-size: 0.82rem; font-weight: 700;
            letter-spacing: 0.03em; cursor: pointer;
            transition: all 0.2s ease;
            display: flex; align-items: center;
            justify-content: center; gap: 9px;
        }

        .btn-submit:hover {
            background: #f0b84a;
            box-shadow: 0 0 32px var(--accent-glow);
            transform: translateY(-1px);
        }

        /* Bottom link */
        .form-bottom {
            text-align: center; margin-top: 20px;
            font-size: 0.82rem; color: var(--text-muted);
        }

        .form-bottom a {
            color: var(--accent); text-decoration: none;
            font-weight: 600; transition: opacity 0.15s;
        }

        .form-bottom a:hover { opacity: 0.8; }

        /* Divider */
        .divider {
            height: 1px; background: var(--border);
            margin: 24px 0; position: relative;
        }

        .divider span {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: var(--bg-card);
            padding: 0 12px;
            font-size: 0.72rem; color: var(--text-dim);
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="page">

        {{-- ══ LEFT PANEL ══ --}}
        <div class="left-panel">
            <a href="{{ url('/') }}" class="panel-logo">
                <span class="logo-dot"></span>
                Yobbal
            </a>

            <div class="panel-content">
                <div class="panel-badge">
                    <span class="logo-dot" style="width:6px;height:6px;"></span>
                    Suivi de colis
                </div>
                <h2 class="panel-title">
                    Gérez vos<br>livraisons avec<br><span class="hl">précision</span>
                </h2>
                <p class="panel-sub">
                    Connectez-vous pour accéder à votre espace et suivre toutes vos expéditions sur le territoire camerounais.
                </p>
                <div class="panel-features">
                    <div class="feat-item">
                        <div class="feat-icon">📍</div>
                        Localisation en temps réel
                    </div>
                    <div class="feat-item">
                        <div class="feat-icon">🔔</div>
                        Alertes à chaque étape
                    </div>
                    <div class="feat-item">
                        <div class="feat-icon">📦</div>
                        Gestion multi-colis
                    </div>
                </div>
            </div>

            <p class="panel-footer">© {{ date('Y') }} Yobbal Logistics — Cameroun</p>
        </div>

        {{-- ══ RIGHT PANEL (Form) ══ --}}
        <div class="right-panel">
            <div class="form-wrapper">

                {{-- Logo mobile --}}
                <a href="{{ url('/') }}" class="mobile-logo">
                    <span class="logo-dot"></span>
                    Yobbal
                </a>

                {{-- Header --}}
                <div class="form-header">
                    <div class="form-tag">Authentification</div>
                    <h1 class="form-title">Bon retour </h1>
                    <p class="form-sub">Connectez-vous à votre compte Yobbal.</p>
                </div>

                {{-- Session status (mot de passe réinitialisé, etc.) --}}
                @if (session('status'))
                    <div class="session-status">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Form card --}}
                <div class="form-card">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="field">
                            <label class="field-label" for="email">Adresse email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="field-input {{ $errors->has('email') ? 'has-error' : '' }}"
                                placeholder="vous@exemple.com"
                                required autofocus autocomplete="username"
                            >
                            @if ($errors->has('email'))
                                <div class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        {{-- Password --}}
                        <div class="field">
                            <label class="field-label" for="password">Mot de passe</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                                placeholder="••••••••"
                                required autocomplete="current-password"
                            >
                            @if ($errors->has('password'))
                                <div class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        {{-- Options --}}
                        <div class="form-options">
                            <label class="remember-label">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="remember-checkbox"
                                >
                                Se souvenir de moi
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn-submit">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                            </svg>
                            Se connecter
                        </button>
                    </form>
                </div>

                {{-- Register link --}}
                @if (Route::has('register'))
                    <p class="form-bottom">
                        Pas encore de compte ?
                        <a href="{{ route('register') }}">Créer un compte →</a>
                    </p>
                @endif

            </div>
        </div>

    </div>
</body>
</html>
