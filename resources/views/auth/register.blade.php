<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription — {{ config('app.name', 'Yobbal') }}</title>

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
            --green:      #22C55E;
            --text:       #EEF2FF;
            --text-muted: #8892B0;
            --text-dim:   #4B5679;
            --border:     rgba(255,255,255,0.07);
            --border-h:   rgba(255,255,255,0.14);
            --font-head:  'Unbounded', sans-serif;
            --font-body:  'Outfit', sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg-deep);
            color: var(--text);
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0; opacity: 0.3;
        }

        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(37,99,235,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37,99,235,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none; z-index: 0;
        }

        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(130px); pointer-events: none; z-index: 0;
        }
        .orb-1 { width: 500px; height: 500px; background: rgba(245,166,35,0.07); top: -100px; left: -100px; animation: floatOrb 16s ease-in-out infinite; }
        .orb-2 { width: 400px; height: 400px; background: rgba(37,99,235,0.09); bottom: 0; right: -80px; animation: floatOrb 20s ease-in-out infinite reverse; }

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

        @media (max-width: 900px) {
            .page { grid-template-columns: 1fr; }
            .left-panel { display: none; }
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            background: linear-gradient(135deg, rgba(37,99,235,0.06) 0%, rgba(245,166,35,0.06) 100%);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
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
            border: 1px solid rgba(34,197,94,0.3);
            background: rgba(34,197,94,0.07);
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: #4ade80; margin-bottom: 24px; width: fit-content;
        }

        .panel-title {
            font-family: var(--font-head);
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            font-weight: 800; letter-spacing: -0.03em;
            line-height: 1.1; margin-bottom: 16px;
        }

        .panel-title .hl { color: var(--accent); }

        .panel-sub {
            font-size: 0.92rem; color: var(--text-muted);
            line-height: 1.7; max-width: 360px; margin-bottom: 36px;
        }

        /* Steps */
        .steps-preview { display: flex; flex-direction: column; gap: 16px; }

        .step-item {
            display: flex; align-items: center; gap: 14px;
            font-size: 0.85rem; color: var(--text-muted);
        }

        .step-num {
            width: 28px; height: 28px; border-radius: 8px;
            background: rgba(245,166,35,0.1);
            border: 1px solid rgba(245,166,35,0.22);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-head); font-size: 0.65rem;
            font-weight: 700; color: var(--accent); flex-shrink: 0;
        }

        .panel-footer { font-size: 0.72rem; color: var(--text-dim); }

        /* ── RIGHT PANEL ── */
        .right-panel {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px 40px; min-height: 100vh;
            overflow-y: auto;
        }

        @media (max-width: 480px) { .right-panel { padding: 32px 20px; } }

        .form-wrapper { width: 100%; max-width: 440px; }

        .mobile-logo {
            display: none; align-items: center; gap: 10px;
            font-family: var(--font-head); font-size: 1.1rem; font-weight: 700;
            color: var(--text); text-decoration: none; margin-bottom: 36px;
        }

        @media (max-width: 900px) { .mobile-logo { display: flex; } }

        /* Form header */
        .form-header { margin-bottom: 28px; }

        .form-tag {
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.13em; text-transform: uppercase;
            color: #4ade80; margin-bottom: 8px;
            display: flex; align-items: center; gap: 8px;
        }

        .form-tag::before {
            content: ''; display: block;
            width: 18px; height: 2px;
            background: #4ade80; border-radius: 2px;
        }

        .form-title {
            font-family: var(--font-head);
            font-size: 1.6rem; font-weight: 700;
            letter-spacing: -0.03em; color: var(--text); margin-bottom: 6px;
        }

        .form-sub { font-size: 0.85rem; color: var(--text-muted); }

        /* Form card */
        .form-card {
            background: var(--bg-glass);
            border: 1px solid var(--border);
            border-radius: 18px; padding: 28px 32px;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 60px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.04);
        }

        @media (max-width: 480px) { .form-card { padding: 22px 16px; } }

        /* Fields */
        .field { margin-bottom: 18px; }
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

        .field-error {
            margin-top: 6px; font-size: 0.75rem; color: #f87171;
            display: flex; align-items: center; gap: 5px;
        }

        /* Password strength indicator */
        .strength-bar {
            display: flex; gap: 4px; margin-top: 8px;
        }

        .strength-seg {
            flex: 1; height: 3px; border-radius: 3px;
            background: var(--border); transition: background 0.3s;
        }

        .strength-seg.weak   { background: #f87171; }
        .strength-seg.medium { background: var(--accent); }
        .strength-seg.strong { background: #4ade80; }

        .strength-label {
            font-size: 0.7rem; color: var(--text-dim);
            margin-top: 4px; text-align: right;
        }

        /* Divider */
        .form-divider {
            height: 1px; background: var(--border); margin: 22px 0;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 14px; border-radius: 11px;
            border: 1px solid var(--accent); background: var(--accent);
            color: #000; font-family: var(--font-head);
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
            text-align: center; margin-top: 18px;
            font-size: 0.82rem; color: var(--text-muted);
        }

        .form-bottom a {
            color: var(--accent); text-decoration: none;
            font-weight: 600; transition: opacity 0.15s;
        }

        .form-bottom a:hover { opacity: 0.8; }

        /* Terms note */
        .terms-note {
            text-align: center; margin-top: 14px;
            font-size: 0.72rem; color: var(--text-dim); line-height: 1.6;
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
                     &nbsp;Inscription gratuite
                </div>
                <h2 class="panel-title">
                    Rejoignez<br>Yobbal et<br><span class="hl">simplifiez</span><br>vos envois
                </h2>
                <p class="panel-sub">
                    Créez votre compte en quelques secondes et commencez à suivre vos colis partout au Cameroun.
                </p>
                <div class="steps-preview">
                    <div class="step-item">
                        <div class="step-num">01</div>
                        Créez votre compte gratuitement
                    </div>
                    <div class="step-item">
                        <div class="step-num">02</div>
                        Enregistrez votre premier colis
                    </div>
                    <div class="step-item">
                        <div class="step-num">03</div>
                        Suivez la livraison en temps réel
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
                    <div class="form-tag">Nouveau compte</div>
                    <h1 class="form-title">Créer un compte </h1>
                    <p class="form-sub">Rejoignez des milliers d'utilisateurs Yobbal.</p>
                </div>

                {{-- Form card --}}
                <div class="form-card">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="field">
                            <label class="field-label" for="name">Nom complet</label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="field-input {{ $errors->has('name') ? 'has-error' : '' }}"
                                placeholder="Jean Dupont"
                                required autofocus autocomplete="name"
                            >
                            @if ($errors->has('name'))
                                <div class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

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
                                required autocomplete="username"
                            >
                            @if ($errors->has('email'))
                                <div class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-divider"></div>

                        {{-- Password --}}
                        <div class="field">
                            <label class="field-label" for="password">Mot de passe</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                                placeholder="Min. 8 caractères"
                                required autocomplete="new-password"
                                oninput="checkStrength(this.value)"
                            >
                            {{-- Strength bar --}}
                            <div class="strength-bar" id="strengthBar">
                                <div class="strength-seg" id="seg1"></div>
                                <div class="strength-seg" id="seg2"></div>
                                <div class="strength-seg" id="seg3"></div>
                                <div class="strength-seg" id="seg4"></div>
                            </div>
                            <div class="strength-label" id="strengthLabel"></div>
                            @if ($errors->has('password'))
                                <div class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        {{-- Confirm Password --}}
                        <div class="field">
                            <label class="field-label" for="password_confirmation">Confirmer le mot de passe</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="field-input {{ $errors->has('password_confirmation') ? 'has-error' : '' }}"
                                placeholder="Répétez le mot de passe"
                                required autocomplete="new-password"
                            >
                            @if ($errors->has('password_confirmation'))
                                <div class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $errors->first('password_confirmation') }}
                                </div>
                            @endif
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn-submit" style="margin-top:22px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                                <path d="M19 8v6M22 11h-6"/>
                            </svg>
                            Créer mon compte
                        </button>

                    </form>
                </div>

                {{-- Login link --}}
                <p class="form-bottom">
                    Déjà un compte ?
                    <a href="{{ route('login') }}">Se connecter →</a>
                </p>

                <p class="terms-note">
                    En créant un compte, vous acceptez les conditions d'utilisation de Yobbal.
                </p>

            </div>
        </div>

    </div>

    <script>
        function checkStrength(val) {
            const segs  = [1,2,3,4].map(i => document.getElementById('seg'+i));
            const label = document.getElementById('strengthLabel');

            // Reset
            segs.forEach(s => s.className = 'strength-seg');
            label.textContent = '';

            if (!val) return;

            let score = 0;
            if (val.length >= 8)              score++;
            if (/[A-Z]/.test(val))            score++;
            if (/[0-9]/.test(val))            score++;
            if (/[^A-Za-z0-9]/.test(val))     score++;

            const configs = [
                null,
                { cls:'weak',   txt:'Faible',    n:1 },
                { cls:'weak',   txt:'Moyen',      n:2 },
                { cls:'medium', txt:'Bien',       n:3 },
                { cls:'strong', txt:'Excellent ✓', n:4 },
            ];

            const c = configs[score];
            if (!c) return;

            for (let i = 0; i < c.n; i++) segs[i].classList.add(c.cls);
            label.textContent   = c.txt;
            label.style.color   = c.cls === 'strong' ? '#4ade80' : c.cls === 'medium' ? 'var(--accent)' : '#f87171';
        }
    </script>
</body>
</html>
