<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suivi colis — Yobbal</title>
    <meta name="description" content="Suivez votre colis Yobbal en temps réel.">

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
            --bg-glass:   rgba(13,22,41,0.85);
            --accent:     #F5A623;
            --accent-dim: rgba(245,166,35,0.12);
            --accent-glow:rgba(245,166,35,0.25);
            --blue:       #2563EB;
            --blue-light: #60A5FA;
            --green:      #22C55E;
            --text:       #EEF2FF;
            --text-muted: #8892B0;
            --text-dim:   #4B5679;
            --border:     rgba(255,255,255,0.07);
            --border-h:   rgba(255,255,255,0.13);
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

        /* Grid bg */
        .grid-bg {
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
            filter: blur(120px); pointer-events: none; z-index: 0;
        }
        .orb-1 { width:500px; height:500px; background:rgba(245,166,35,0.07); top:-150px; right:-100px; }
        .orb-2 { width:400px; height:400px; background:rgba(37,99,235,0.08);  bottom:50px; left:-100px; }

        .wrapper { position: relative; z-index: 1; flex: 1; display: flex; flex-direction: column; }

        /* ── NAVBAR ── */
        nav.navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5vw;
            height: 68px;
            background: rgba(6,11,24,0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
        }

        .nav-logo {
            font-family: var(--font-head);
            font-size: 1.2rem; font-weight: 700;
            color: var(--text); text-decoration: none;
            display: flex; align-items: center; gap: 10px;
        }

        .logo-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--accent); box-shadow: 0 0 12px var(--accent);
            animation: pDot 2s ease-in-out infinite;
        }

        @keyframes pDot {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:.6; transform:scale(1.4); }
        }

        .nav-links { display: flex; align-items: center; gap: 10px; }

        .btn-ghost {
            padding: 8px 18px; border-radius: 8px;
            border: 1px solid var(--border); background: transparent;
            color: var(--text-muted); font-family: var(--font-body);
            font-size: 0.85rem; font-weight: 500;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-ghost:hover { border-color: var(--border-h); color: var(--text); background: rgba(255,255,255,0.04); }

        .btn-accent {
            padding: 8px 20px; border-radius: 8px;
            border: 1px solid var(--accent); background: var(--accent);
            color: #000; font-family: var(--font-body);
            font-size: 0.85rem; font-weight: 600;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-accent:hover { background: #f0b84a; box-shadow: 0 0 24px var(--accent-glow); }

        /* ── MAIN ── */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 5vw 80px;
        }

        /* ── SEARCH BOX ── */
        .search-section {
            width: 100%; max-width: 620px;
            text-align: center;
            margin-bottom: 52px;
        }

        .search-badge {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 5px 14px; border-radius: 999px;
            border: 1px solid rgba(245,166,35,0.3);
            background: rgba(245,166,35,0.07);
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--accent); margin-bottom: 20px;
        }

        .search-title {
            font-family: var(--font-head);
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            font-weight: 800; letter-spacing: -0.03em;
            line-height: 1.1; margin-bottom: 12px;
        }

        .search-sub {
            font-size: 0.9rem; color: var(--text-muted);
            margin-bottom: 28px; line-height: 1.7;
        }

        .search-box {
            background: var(--bg-glass);
            border: 1px solid var(--border);
            border-radius: 18px; padding: 20px 24px;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 60px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.04);
        }

        .search-row { display: flex; gap: 10px; }

        .search-input {
            flex: 1;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 10px; padding: 13px 18px;
            color: var(--text); font-family: var(--font-body);
            font-size: 0.95rem; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-input::placeholder { color: var(--text-dim); }
        .search-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .search-btn {
            padding: 13px 22px; border-radius: 10px;
            border: none; background: var(--accent); color: #000;
            font-family: var(--font-head); font-size: 0.75rem; font-weight: 700;
            cursor: pointer; letter-spacing: 0.03em;
            display: flex; align-items: center; gap: 7px;
            transition: all 0.2s; white-space: nowrap;
        }
        .search-btn:hover { background: #f0b84a; box-shadow: 0 0 28px var(--accent-glow); transform: translateY(-1px); }

        /* ── ERROR ── */
        .error-box {
            width: 100%; max-width: 620px;
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.25);
            border-radius: var(--radius); padding: 16px 20px;
            display: flex; align-items: flex-start; gap: 12px;
            margin-bottom: 32px; animation: fadeUp 0.4s ease;
        }

        .error-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
        .error-text { font-size: 0.85rem; color: #fca5a5; line-height: 1.6; }

        /* ── RESULT CARD ── */
        .result-section {
            width: 100%; max-width: 720px;
            animation: fadeUp 0.5s ease;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Result header */
        .result-header {
            display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap;
            gap: 12px; margin-bottom: 20px;
        }

        .result-ref {
            font-family: var(--font-head);
            font-size: 0.72rem; color: var(--accent);
            letter-spacing: 0.06em; margin-bottom: 4px;
        }

        .result-title {
            font-family: var(--font-head);
            font-size: 1.4rem; font-weight: 700;
            letter-spacing: -0.02em; color: var(--text);
        }

        /* Badge statut */
        .badge {
            padding: 6px 14px; border-radius: 999px;
            font-size: 0.68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            display: inline-flex; align-items: center; gap: 7px;
        }

        .badge-transit   { background: rgba(37,99,235,0.15);  color: #93c5fd; border: 1px solid rgba(37,99,235,0.25); }
        .badge-delivered { background: rgba(34,197,94,0.12);  color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
        .badge-pending   { background: rgba(245,166,35,0.12); color: var(--accent); border: 1px solid rgba(245,166,35,0.25); }
        .badge-returned  { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.2); }

        .badge-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: currentColor; animation: pDot 2s ease-in-out infinite;
        }

        /* Route card */
        .route-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 24px 28px;
            display: flex; align-items: center;
            margin-bottom: 16px; position: relative; overflow: hidden;
        }

        .route-card::before {
            content: '';
            position: absolute; top:0; left:0; right:0; height:2px;
            background: linear-gradient(90deg, var(--accent), transparent);
        }

        .route-city { flex: 1; text-align: center; }
        .city-tag { font-size: 0.62rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-dim); margin-bottom: 5px; }
        .city-name { font-family: var(--font-head); font-size: 1.3rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text); }

        .route-mid {
            display: flex; flex-direction: column;
            align-items: center; gap: 4px; padding: 0 24px;
        }

        .route-line-wrap {
            position: relative; width: 80px; height: 2px;
            background: linear-gradient(90deg, var(--border), var(--accent), var(--border));
        }

        .route-emoji {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -60%);
            background: var(--bg-card); padding: 0 4px;
            font-size: 1rem;
        }

        .route-poids { font-size: 0.72rem; color: var(--text-muted); margin-top: 8px; }

        /* Progress tracking */
        .tracking-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 28px;
            margin-bottom: 16px;
        }

        .tracking-label {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--text-dim); margin-bottom: 24px;
        }

        .steps { display: flex; align-items: flex-start; }

        .step {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; gap: 8px; position: relative; z-index: 1;
        }

        .step:not(:last-child)::after {
            content: '';
            position: absolute; top: 17px; left: 50%;
            width: 100%; height: 2px;
            background: var(--border); z-index: 0;
        }

        .step.done:not(:last-child)::after { background: var(--accent); }
        .step.active:not(:last-child)::after { background: linear-gradient(90deg, var(--accent), var(--border)); }

        .step-circle {
            width: 34px; height: 34px; border-radius: 50%;
            border: 2px solid var(--border);
            background: var(--bg-deep);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; position: relative; z-index: 2;
            transition: all 0.3s;
        }

        .step.done .step-circle {
            border-color: var(--accent); background: rgba(245,166,35,0.1);
            box-shadow: 0 0 14px rgba(245,166,35,0.3);
        }

        .step.active .step-circle {
            border-color: var(--accent); background: var(--accent);
            box-shadow: 0 0 20px rgba(245,166,35,0.5);
        }

        .step-lbl { font-size: 0.68rem; font-weight: 600; color: var(--text-dim); text-align: center; line-height: 1.3; }
        .step.done .step-lbl, .step.active .step-lbl { color: var(--text-muted); }
        .step.active .step-lbl { color: var(--accent); }

        .step-date { font-size: 0.62rem; color: var(--text-dim); text-align: center; }

        /* Info grid */
        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 14px; margin-bottom: 16px;
        }

        @media (max-width: 540px) {
            .info-grid { grid-template-columns: 1fr; }
            .search-row { flex-direction: column; }
            .route-card { flex-direction: column; gap: 16px; }
            .route-mid { flex-direction: row; padding: 0; }
            .route-line-wrap { width: 50px; }
        }

        .info-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 20px 22px;
        }

        .info-card-lbl {
            font-size: 0.62rem; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--text-dim); margin-bottom: 14px;
        }

        .info-row {
            display: flex; align-items: center;
            justify-content: space-between; gap: 10px;
            padding: 8px 0; border-bottom: 1px solid var(--border);
        }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-row:first-of-type { padding-top: 0; }

        .info-key { font-size: 0.77rem; color: var(--text-dim); }
        .info-val { font-size: 0.83rem; color: var(--text); font-weight: 600; text-align: right; }

        /* Footer */
        footer {
            padding: 28px 5vw;
            border-top: 1px solid var(--border);
            display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap;
            gap: 12px; font-size: 0.75rem; color: var(--text-dim);
            position: relative; z-index: 1;
        }

        .footer-logo {
            font-family: var(--font-head); font-size: 1rem; font-weight: 700;
            color: var(--text); text-decoration: none;
            display: flex; align-items: center; gap: 8px;
        }
    </style>
</head>
<body>
    <div class="grid-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="wrapper">

        {{-- NAVBAR --}}
        <nav class="navbar">
            <a href="{{ url('/') }}" class="nav-logo">
                <span class="logo-dot"></span>
                Yobbal
            </a>
            <div class="nav-links">
                @auth
                    <a href="{{ url('/home') }}" class="btn-accent">Mon espace →</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Connexion</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-accent">S'inscrire</a>
                    @endif
                @endauth
            </div>
        </nav>

        {{-- MAIN --}}
        <main class="main">

            {{-- Search section --}}
            <div class="search-section">
                <div class="search-badge">
                    <span class="logo-dot" style="width:6px;height:6px;"></span>
                    Localisation de colis
                </div>
                <h1 class="search-title">Où est votre colis ?</h1>
                <p class="search-sub">Entrez votre numéro de suivi pour connaître la position exacte de votre envoi.</p>

                <div class="search-box">
                    <form method="GET" action="{{ route('tracking.index') }}">
                        <div class="search-row">
                            <input
                                type="text"
                                name="n"
                                class="search-input"
                                placeholder="Ex : YBL-000001"
                                value="{{ $numero ?? '' }}"
                                autofocus
                            >
                            <button type="submit" class="search-btn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                                </svg>
                                Localiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Erreur --}}
            @if ($error)
                <div class="error-box">
                    <span class="error-icon">⚠️</span>
                    <span class="error-text">{{ $error }}</span>
                </div>
            @endif

            {{-- Résultat --}}
            @if ($colis)
                @php
                    $statusMap = [
                        'en_transit' => ['label' => 'En transit',  'class' => 'badge-transit',   'emoji' => '🚚', 'step' => 2],
                        'livre'      => ['label' => 'Livré',        'class' => 'badge-delivered', 'emoji' => '✅', 'step' => 3],
                        'en_attente' => ['label' => 'En attente',   'class' => 'badge-pending',   'emoji' => '⏳', 'step' => 0],
                        'retourne'   => ['label' => 'Retourné',     'class' => 'badge-returned',  'emoji' => '↩️', 'step' => 1],
                    ];
                    $st      = $statusMap[$colis->statut ?? 'en_attente'] ?? $statusMap['en_attente'];
                    $curStep = $st['step'];

                    $steps = [
                        ['icon' => '📋', 'label' => 'Enregistré',  'date' => $colis->created_at?->format('d M')],
                        ['icon' => '📦', 'label' => 'En entrepôt', 'date' => null],
                        ['icon' => '🚚', 'label' => 'En transit',  'date' => null],
                        ['icon' => '✅', 'label' => 'Livré',       'date' => $colis->statut === 'livre' ? $colis->updated_at?->format('d M') : null],
                    ];
                @endphp

                <div class="result-section">

                    {{-- Header résultat --}}
                    <div class="result-header">
                        <div>
                            <div class="result-ref">YBL-{{ str_pad($colis->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <div class="result-title">
                                {{ $colis->expediteur }} → {{ $colis->destinataire }}
                            </div>
                        </div>
                        <span class="badge {{ $st['class'] }}">
                            <span class="badge-dot"></span>
                            {{ $st['label'] }}
                        </span>
                    </div>

                    {{-- Route visuelle --}}
                    <div class="route-card">
                        <div class="route-city">
                            <div class="city-tag">Départ</div>
                            <div class="city-name">{{ $colis->ville_depart }}</div>
                        </div>
                        <div class="route-mid">
                            <div class="route-line-wrap">
                                <span class="route-emoji">{{ $st['emoji'] }}</span>
                            </div>
                            <div class="route-poids">{{ $colis->poids }} kg</div>
                        </div>
                        <div class="route-city">
                            <div class="city-tag">Arrivée</div>
                            <div class="city-name">{{ $colis->ville_arrivee }}</div>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="tracking-card">
                        <div class="tracking-label">Progression de la livraison</div>
                        <div class="steps">
                            @foreach($steps as $i => $step)
                                <div class="step {{ $i < $curStep ? 'done' : ($i === $curStep ? 'active' : '') }}">
                                    <div class="step-circle">
                                        @if($i < $curStep)
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.5">
                                                <path d="M20 6L9 17l-5-5"/>
                                            </svg>
                                        @else
                                            {{ $step['icon'] }}
                                        @endif
                                    </div>
                                    <div class="step-lbl">{{ $step['label'] }}</div>
                                    @if($step['date'])
                                        <div class="step-date">{{ $step['date'] }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Info grid --}}
                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-card-lbl">👥 Personnes</div>
                            <div class="info-row">
                                <span class="info-key">Expéditeur</span>
                                <span class="info-val">{{ $colis->expediteur }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Destinataire</span>
                                <span class="info-val">{{ $colis->destinataire }}</span>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-card-lbl">📅 Dates</div>
                            <div class="info-row">
                                <span class="info-key">Enregistré</span>
                                <span class="info-val">{{ $colis->created_at?->format('d M Y') ?? '—' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Mise à jour</span>
                                <span class="info-val">{{ $colis->updated_at?->diffForHumans() ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </main>

        {{-- FOOTER --}}
        <footer>
            <a href="{{ url('/') }}" class="footer-logo">
                <span class="logo-dot"></span>
                Yobbal
            </a>
            <span>© {{ date('Y') }} Yobbal Logistics — Cameroun</span>
        </footer>

    </div>
</body>
</html>
