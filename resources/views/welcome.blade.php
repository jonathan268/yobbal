<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yobbal — Suivez vos colis en temps réel</title>
    <meta name="description"
        content="Yobbal est la plateforme intelligente de suivi de colis. Localisez, tracez et gérez toutes vos livraisons en un seul endroit.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700;900&family=Outfit:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-deep: #060B18;
            --bg-card: #0D1629;
            --bg-glass: rgba(13, 22, 41, 0.7);
            --accent: #F5A623;
            --accent-dim: #7C4D0F;
            --accent-glow: rgba(245, 166, 35, 0.25);
            --blue: #2563EB;
            --blue-light: #60A5FA;
            --text: #EEF2FF;
            --text-muted: #8892B0;
            --border: rgba(255, 255, 255, 0.08);
            --radius: 16px;
            --font-head: 'Unbounded', sans-serif;
            --font-body: 'Outfit', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-deep);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ── NOISE OVERLAY ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        /* ── GRID BG ── */
        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(37, 99, 235, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37, 99, 235, 0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── GLOW ORBS ── */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            z-index: 0;
        }

        .orb-1 {
            width: 600px;
            height: 600px;
            background: rgba(245, 166, 35, 0.08);
            top: -200px;
            right: -100px;
            animation: floatOrb 12s ease-in-out infinite;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: rgba(37, 99, 235, 0.1);
            bottom: 100px;
            left: -150px;
            animation: floatOrb 15s ease-in-out infinite reverse;
        }

        @keyframes floatOrb {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(30px, -40px);
            }
        }

        /* ── WRAPPER ── */
        .wrapper {
            position: relative;
            z-index: 1;
        }

        /* ── NAVBAR ── */
        nav.navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5vw;
            height: 72px;
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(6, 11, 24, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .nav-logo {
            font-family: var(--font-head);
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-logo .logo-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 12px var(--accent);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.3);
            }
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-ghost {
            padding: 8px 20px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            font-family: var(--font-body);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-ghost:hover {
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--text);
            background: rgba(255, 255, 255, 0.04);
        }

        .btn-accent {
            padding: 8px 22px;
            border-radius: 8px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #000;
            font-family: var(--font-body);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-accent:hover {
            background: #f0b84a;
            box-shadow: 0 0 24px var(--accent-glow);
            transform: translateY(-1px);
        }

        /* ── HERO ── */
        .hero {
            min-height: calc(100vh - 72px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 5vw 60px;
            text-align: center;
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 999px;
            border: 1px solid rgba(245, 166, 35, 0.3);
            background: rgba(245, 166, 35, 0.08);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 32px;
            animation: fadeUp 0.6s ease both;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        .hero-title {
            font-family: var(--font-head);
            font-size: clamp(2.4rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -0.03em;
            max-width: 800px;
            margin-bottom: 24px;
            animation: fadeUp 0.6s 0.1s ease both;
        }

        .hero-title .highlight {
            color: var(--accent);
            position: relative;
            display: inline-block;
        }

        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), transparent);
            border-radius: 2px;
        }

        .hero-sub {
            font-size: 1.125rem;
            color: var(--text-muted);
            max-width: 520px;
            margin-bottom: 48px;
            font-weight: 400;
            animation: fadeUp 0.6s 0.2s ease both;
        }

        /* ── TRACKING BOX ── */
        .track-box {
            width: 100%;
            max-width: 580px;
            background: var(--bg-glass);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 60px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            animation: fadeUp 0.6s 0.3s ease both;
            margin-bottom: 64px;
        }

        .track-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
            text-align: left;
        }

        .track-input-row {
            display: flex;
            gap: 10px;
        }

        .track-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 18px;
            color: var(--text);
            font-family: var(--font-body);
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .track-input::placeholder {
            color: var(--text-muted);
        }

        .track-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .track-btn {
            padding: 14px 24px;
            border-radius: 10px;
            border: none;
            background: var(--accent);
            color: #000;
            font-family: var(--font-head);
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            letter-spacing: 0.03em;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .track-btn:hover {
            background: #f0b84a;
            box-shadow: 0 0 30px var(--accent-glow);
            transform: translateY(-1px);
        }

        .track-hint {
            margin-top: 14px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: left;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── STATS ── */
        .stats-bar {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeUp 0.6s 0.4s ease both;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-family: var(--font-head);
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .stat-divider {
            width: 1px;
            background: var(--border);
            align-self: stretch;
        }

        /* ── SECTION ── */
        section {
            padding: 100px 5vw;
            position: relative;
        }

        .section-tag {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-tag::before {
            content: '';
            display: block;
            width: 24px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
        }

        .section-title {
            font-family: var(--font-head);
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .section-sub {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 480px;
            line-height: 1.7;
        }

        /* ── FEATURES ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 60px;
        }

        @media (max-width: 900px) {
            .features-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 580px) {
            .features-grid {
                grid-template-columns: 1fr;
            }

            .track-input-row {
                flex-direction: column;
            }
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(245, 166, 35, 0.4), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover {
            border-color: rgba(245, 166, 35, 0.25);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), 0 0 40px var(--accent-glow);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid rgba(245, 166, 35, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .feature-title {
            font-family: var(--font-head);
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            margin-bottom: 12px;
            color: var(--text);
        }

        .feature-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* ── HOW IT WORKS ── */
        .how-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .how-layout {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }

        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .step-item {
            display: flex;
            gap: 20px;
            padding: 28px 0;
            border-bottom: 1px solid var(--border);
            position: relative;
        }

        .step-item:last-child {
            border-bottom: none;
        }

        .step-num {
            font-family: var(--font-head);
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--accent);
            background: rgba(245, 166, 35, 0.1);
            border: 1px solid rgba(245, 166, 35, 0.25);
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-content h3 {
            font-family: var(--font-head);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text);
        }

        .step-content p {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* ── MOCKUP SCREEN ── */
        .mockup-screen {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.6);
        }

        .mockup-header {
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mockup-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .mockup-dot:nth-child(1) {
            background: #FF5F57;
        }

        .mockup-dot:nth-child(2) {
            background: #FEBC2E;
        }

        .mockup-dot:nth-child(3) {
            background: #28C840;
        }

        .mockup-body {
            padding: 24px;
        }

        .track-status-card {
            background: rgba(245, 166, 35, 0.06);
            border: 1px solid rgba(245, 166, 35, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .track-id {
            font-family: var(--font-head);
            font-size: 0.75rem;
            color: var(--accent);
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .track-status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .track-status-text {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .status-transit {
            background: rgba(37, 99, 235, 0.2);
            color: var(--blue-light);
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        .status-delivered {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .progress-track {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
        }

        .progress-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: var(--bg-deep);
            position: relative;
            z-index: 1;
        }

        .progress-circle.done {
            border-color: var(--accent);
            background: var(--accent);
        }

        .progress-circle.active {
            border-color: var(--accent);
            background: transparent;
            box-shadow: 0 0 10px var(--accent);
        }

        .progress-line {
            flex: 1;
            height: 2px;
            background: var(--border);
            margin-top: -8px;
        }

        .progress-line.done {
            background: var(--accent);
        }

        .progress-label {
            font-size: 0.6rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .mini-parcel-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
        }

        .mini-parcel-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(37, 99, 235, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .mini-parcel-info {
            flex: 1;
        }

        .mini-parcel-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 2px;
        }

        .mini-parcel-sub {
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        /* ── CTA ── */
        .cta-section {
            text-align: center;
            background: linear-gradient(135deg, rgba(245, 166, 35, 0.06) 0%, rgba(37, 99, 235, 0.06) 100%);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .cta-title {
            font-family: var(--font-head);
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
            line-height: 1.1;
        }

        .cta-sub {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 40px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-cta-primary {
            padding: 14px 32px;
            border-radius: 12px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #000;
            font-family: var(--font-head);
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 40px var(--accent-glow);
        }

        .btn-cta-ghost {
            padding: 14px 32px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            font-family: var(--font-head);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
        }

        .btn-cta-ghost:hover {
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.04);
            transform: translateY(-2px);
        }

        /* ── FOOTER ── */
        footer {
            padding: 40px 5vw;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-logo {
            font-family: var(--font-head);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-copy {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .footer-links {
            display: flex;
            gap: 24px;
        }

        .footer-link {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--text);
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── SEPARATOR ── */
        .sep {
            width: 1px;
            height: 40px;
            background: var(--border);
        }
    </style>
</head>

<body>
    <div class="grid-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="wrapper">

        <!-- ══ NAVBAR ══ -->
        <nav class="navbar">
            <a href="/" class="nav-logo">
                <span class="logo-dot"></span>
                Yobbal
            </a>

            <div class="nav-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn-accent">
                            Mon espace →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">
                            Connexion
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-accent">
                                Commencer
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- ══ HERO ══ -->
        <section class="hero">
            <div class="hero-badge">
                <span class="badge-dot"></span>
                Suivi de colis en temps réel
            </div>

            <h1 class="hero-title">
                Vos colis, <span class="highlight">toujours</span><br>sous contrôle
            </h1>

            <p class="hero-sub">
                Yobbal centralise toutes vos livraisons en un seul tableau de bord.
                Suivez, anticipez, et soyez alerté à chaque étape.
            </p>

            <!-- Track box -->
            <div class="track-box">
                <div class="track-label">Numéro de suivi</div>
                <div class="track-input-row">
                    <input type="text" class="track-input" placeholder="Ex : YBL-2024-CMRX8471" id="trackingInput">
                    <button class="track-btn" onclick="handleTrack()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        Localiser
                    </button>
                </div>
                <div class="track-hint">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4m0-4h.01" />
                    </svg>
                    Trouvez votre numéro sur le SMS ou email de confirmation
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number">120k+</div>
                    <div class="stat-label">Colis suivis</div>
                </div>
                <div class="sep"></div>
                <div class="stat-item">
                    <div class="stat-number">98.4%</div>
                    <div class="stat-label">Livraisons à temps</div>
                </div>
                <div class="sep"></div>
                <div class="stat-item">
                    <div class="stat-number">47</div>
                    <div class="stat-label">Transporteurs</div>
                </div>
                <div class="sep"></div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Disponibilité</div>
                </div>
            </div>
        </section>

        <!-- ══ FEATURES ══ -->
        <section>
            <div class="section-tag">Fonctionnalités</div>
            <h2 class="section-title">Tout ce dont vous<br>avez besoin</h2>
            <p class="section-sub">
                De la commande à la livraison, Yobbal vous offre une visibilité totale sur l'ensemble de vos envois.
            </p>

            <div class="features-grid">
                <div class="feature-card fade-up">
                    <div class="feature-icon">📍</div>
                    <div class="feature-title">Suivi GPS en direct</div>
                    <p class="feature-desc">Localisez votre colis sur une carte interactive, mise à jour en temps réel à
                        chaque scan du transporteur.</p>
                </div>
                <div class="feature-card fade-up" style="transition-delay:0.1s">
                    <div class="feature-icon">🔔</div>
                    <div class="feature-title">Alertes intelligentes</div>
                    <p class="feature-desc">Recevez des notifications SMS, email ou push à chaque changement d'état :
                        expédié, en transit, livré.</p>
                </div>
                <div class="feature-card fade-up" style="transition-delay:0.2s">
                    <div class="feature-icon">📦</div>
                    <div class="feature-title">Multi-transporteurs</div>
                    <p class="feature-desc">Compatible avec 47+ transporteurs locaux et internationaux. Un seul
                        dashboard pour tout centraliser.</p>
                </div>
                <div class="feature-card fade-up" style="transition-delay:0.3s">
                    <div class="feature-icon">📊</div>
                    <div class="feature-title">Analytics & rapports</div>
                    <p class="feature-desc">Visualisez vos performances de livraison, identifiez les retards et
                        optimisez vos flux logistiques.</p>
                </div>
                <div class="feature-card fade-up" style="transition-delay:0.4s">
                    <div class="feature-icon">🔐</div>
                    <div class="feature-title">Accès sécurisé</div>
                    <p class="feature-desc">Authentification robuste, données chiffrées, et contrôle d'accès par rôle
                        pour vos équipes.</p>
                </div>
                <div class="feature-card fade-up" style="transition-delay:0.5s">
                    <div class="feature-icon">🔗</div>
                    <div class="feature-title">API & intégrations</div>
                    <p class="feature-desc">Connectez Yobbal à votre boutique, ERP ou CRM via notre API REST documentée
                        et nos webhooks.</p>
                </div>
            </div>
        </section>

        <!-- ══ HOW IT WORKS ══ -->
        <section
            style="background: rgba(255,255,255,0.01); border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
            <div class="how-layout">
                <div>
                    <div class="section-tag">Comment ça marche</div>
                    <h2 class="section-title">Simple comme<br>bonjour</h2>
                    <div class="steps-list" style="margin-top:40px;">
                        <div class="step-item fade-up">
                            <div class="step-num">01</div>
                            <div class="step-content">
                                <h3>Créez votre compte</h3>
                                <p>Inscription gratuite en 30 secondes, aucune carte bancaire requise.</p>
                            </div>
                        </div>
                        <div class="step-item fade-up">
                            <div class="step-num">02</div>
                            <div class="step-content">
                                <h3>Ajoutez votre numéro de suivi</h3>
                                <p>Collez le code reçu par SMS ou email et Yobbal identifie automatiquement le
                                    transporteur.</p>
                            </div>
                        </div>
                        <div class="step-item fade-up">
                            <div class="step-num">03</div>
                            <div class="step-content">
                                <h3>Suivez en temps réel</h3>
                                <p>Votre colis est sur la carte. Recevez des alertes à chaque étape jusqu'à la
                                    livraison.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mockup -->
                <div class="mockup-screen fade-up">
                    <div class="mockup-header">
                        <div class="mockup-dot"></div>
                        <div class="mockup-dot"></div>
                        <div class="mockup-dot"></div>
                    </div>
                    <div class="mockup-body">
                        <div class="track-status-card">
                            <div class="track-id">YBL-2024-CMRX8471</div>
                            <div class="track-status-row">
                                <span class="track-status-text">En transit</span>
                                <span class="status-badge status-transit">● En route</span>
                            </div>
                            <!-- Progress -->
                            <div style="display:flex; align-items:flex-start; gap:0;">
                                <div class="progress-step">
                                    <div class="progress-circle done"></div>
                                    <div class="progress-label">Expédié</div>
                                </div>
                                <div class="progress-line done" style="margin-top:5px;"></div>
                                <div class="progress-step">
                                    <div class="progress-circle done"></div>
                                    <div class="progress-label">Tri</div>
                                </div>
                                <div class="progress-line done" style="margin-top:5px;"></div>
                                <div class="progress-step">
                                    <div class="progress-circle active"></div>
                                    <div class="progress-label">Transit</div>
                                </div>
                                <div class="progress-line" style="margin-top:5px;"></div>
                                <div class="progress-step">
                                    <div class="progress-circle"></div>
                                    <div class="progress-label">Livré</div>
                                </div>
                            </div>
                        </div>

                        <div class="mini-parcel-card">
                            <div class="mini-parcel-icon">📱</div>
                            <div class="mini-parcel-info">
                                <div class="mini-parcel-name">Commande #1047 — Électronique</div>
                                <div class="mini-parcel-sub">Douala → Yaoundé · Arrivée prévue : 14 Mar</div>
                            </div>
                            <span class="status-badge status-transit">Transit</span>
                        </div>

                        <div class="mini-parcel-card">
                            <div class="mini-parcel-icon">👗</div>
                            <div class="mini-parcel-info">
                                <div class="mini-parcel-name">Commande #1038 — Vêtements</div>
                                <div class="mini-parcel-sub">Paris → Douala · Livré le 10 Mar</div>
                            </div>
                            <span class="status-badge status-delivered">Livré</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══ CTA ══ -->
        <section class="cta-section">
            <h2 class="cta-title">Prêt à ne plus perdre<br>aucun colis ?</h2>
            <p class="cta-sub">Rejoignez des milliers d'utilisateurs qui font confiance à Yobbal pour leurs livraisons.
            </p>
            <div class="cta-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn-cta-primary">
                            Accéder à mon dashboard →
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-cta-primary">
                                Créer un compte gratuit →
                            </a>
                        @endif
                        <a href="{{ route('login') }}" class="btn-cta-ghost">
                            Se connecter
                        </a>
                    @endauth
                @else
                    <a href="#" class="btn-cta-primary">Démarrer gratuitement →</a>
                @endif
            </div>
        </section>

        <!-- ══ FOOTER ══ -->
        <footer>
            <a href="/" class="footer-logo">
                <span class="logo-dot"></span>
                Yobbal
            </a>
            <div class="footer-links">
                <a href="#" class="footer-link">Mentions légales</a>
                <a href="#" class="footer-link">Confidentialité</a>
                <a href="#" class="footer-link">Contact</a>
                <a href="#" class="footer-link">API</a>
            </div>
            <p class="footer-copy">© {{ date('Y') }} Yobbal. Tous droits réservés.</p>
        </footer>

    </div><!-- /wrapper -->

    <script>
        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

        // Track button
        function handleTrack() {
            const val = document.getElementById('trackingInput').value.trim();
            if (!val) {
                document.getElementById('trackingInput').focus();
                return;
            }
            // Redirect to tracking route — adapt to your actual route
            window.location.href = '/track?n=' + encodeURIComponent(val);
        }

        document.getElementById('trackingInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') handleTrack();
        });

        const TRACK_URL = "{{ route('tracking.index') }}";

        function handleTrack() {
            const val = document.getElementById('trackingInput').value.trim();
            if (!val) {
                document.getElementById('trackingInput').focus();
                return;
            }
            window.location.href = TRACK_URL + '?n=' + encodeURIComponent(val);
        }

        document.getElementById('trackingInput').addEventListener('keydown', e => {
            if (e.key === 'Enter') handleTrack();
        });
    </script>
</body>

</html>
