@extends('layouts.app')

@section('title', 'Détails du Colis')

@push('styles')
<style>
    .show-container {
        max-width: 760px;
        margin: 0 auto;
    }

    /* ── BREADCRUMB ── */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        font-size: 0.78rem;
        color: var(--text-dim);
    }

    .breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
        transition: color 0.15s;
    }

    .breadcrumb a:hover { color: var(--accent); }
    .breadcrumb .sep { opacity: 0.4; }
    .breadcrumb .current { color: var(--text); font-weight: 500; }

    /* ── PAGE HEADER ── */
    .page-greeting {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.13em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-greeting::before {
        content: '';
        display: block;
        width: 20px; height: 2px;
        background: var(--accent);
        border-radius: 2px;
    }

    .page-title-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .page-title {
        font-family: var(--font-head);
        font-size: clamp(1.4rem, 3vw, 1.9rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--text);
        line-height: 1.1;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* ── BADGES ── */
    .badge {
        padding: 5px 13px;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .badge-transit   { background: rgba(37,99,235,0.15);  color: #93c5fd; border: 1px solid rgba(37,99,235,0.25); }
    .badge-delivered { background: rgba(34,197,94,0.12);  color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
    .badge-pending   { background: rgba(245,166,35,0.12); color: var(--accent); border: 1px solid rgba(245,166,35,0.25); }
    .badge-returned  { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.2); }

    .badge .badge-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
        animation: pulseDot 2s ease-in-out infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.5; transform: scale(1.4); }
    }

    /* ── TRACKING PROGRESS ── */
    .tracking-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 28px 32px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .tracking-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, var(--accent), transparent);
    }

    .tracking-title {
        font-family: var(--font-head);
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 24px;
    }

    /* Progress steps */
    .progress-steps {
        display: flex;
        align-items: flex-start;
        position: relative;
    }

    .progress-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 1;
    }

    /* Connector line */
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 18px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: var(--border);
        z-index: 0;
    }

    .progress-step.done:not(:last-child)::after,
    .progress-step.active:not(:last-child)::after {
        background: linear-gradient(90deg, var(--accent), var(--border));
    }

    .progress-step.done:not(:last-child)::after {
        background: var(--accent);
    }

    .step-circle {
        width: 36px; height: 36px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: var(--bg-deep);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
        transition: all 0.3s;
        position: relative;
        z-index: 2;
    }

    .progress-step.done .step-circle {
        border-color: var(--accent);
        background: rgba(245,166,35,0.1);
        box-shadow: 0 0 16px rgba(245,166,35,0.3);
    }

    .progress-step.active .step-circle {
        border-color: var(--accent);
        background: var(--accent);
        box-shadow: 0 0 20px rgba(245,166,35,0.5);
    }

    .step-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-dim);
        text-align: center;
        line-height: 1.3;
    }

    .progress-step.done .step-label,
    .progress-step.active .step-label {
        color: var(--text-muted);
    }

    .progress-step.active .step-label {
        color: var(--accent);
    }

    .step-date {
        font-size: 0.65rem;
        color: var(--text-dim);
        text-align: center;
    }

    /* ── INFO GRID ── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    @media (max-width: 580px) {
        .info-grid { grid-template-columns: 1fr; }
        .progress-steps { gap: 0; }
        .step-label { font-size: 0.62rem; }
        .tracking-card { padding: 20px; }
    }

    .info-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px 24px;
        transition: border-color 0.2s, transform 0.2s;
    }

    .info-card:hover {
        border-color: var(--border-hover);
        transform: translateY(-2px);
    }

    .info-card-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-dim);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 0;
        border-bottom: 1px solid var(--border);
        gap: 12px;
    }

    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-row:first-of-type { padding-top: 0; }

    .info-key {
        font-size: 0.78rem;
        color: var(--text-dim);
        font-weight: 500;
        flex-shrink: 0;
    }

    .info-val {
        font-size: 0.85rem;
        color: var(--text);
        font-weight: 600;
        text-align: right;
    }

    /* ── ROUTE VISUAL ── */
    .route-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 0;
    }

    .route-city {
        flex: 1;
        text-align: center;
    }

    .city-tag {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--text-dim);
        margin-bottom: 6px;
    }

    .city-name {
        font-family: var(--font-head);
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text);
        letter-spacing: -0.02em;
    }

    .route-middle {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 24px;
        gap: 4px;
    }

    .route-line {
        width: 80px;
        height: 2px;
        background: linear-gradient(90deg, var(--border), var(--accent), var(--border));
        border-radius: 2px;
        position: relative;
    }

    .route-icon {
        font-size: 1.1rem;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -60%);
        background: var(--bg-card);
        padding: 0 4px;
    }

    .route-weight {
        font-size: 0.72rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 6px;
    }

    /* ── ACTION PANEL ── */
    .action-panel {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .action-left {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 9px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        font-family: var(--font-head);
        font-size: 0.72rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.18s;
        letter-spacing: 0.02em;
    }

    .btn-back:hover {
        border-color: var(--border-hover);
        color: var(--text);
        background: rgba(255,255,255,0.04);
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        border-radius: 9px;
        border: 1px solid rgba(96,165,250,0.3);
        background: rgba(37,99,235,0.12);
        color: #93c5fd;
        font-family: var(--font-head);
        font-size: 0.72rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.18s;
        letter-spacing: 0.02em;
    }

    .btn-edit:hover {
        background: rgba(37,99,235,0.2);
        border-color: rgba(96,165,250,0.5);
        transform: translateY(-1px);
        box-shadow: 0 0 20px rgba(37,99,235,0.2);
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        border-radius: 9px;
        border: 1px solid rgba(248,113,113,0.25);
        background: rgba(248,113,113,0.06);
        color: #f87171;
        font-family: var(--font-head);
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.02em;
        transition: all 0.18s;
        font-family: var(--font-head);
    }

    .btn-delete:hover {
        background: rgba(248,113,113,0.12);
        border-color: rgba(248,113,113,0.4);
        transform: translateY(-1px);
    }

    /* ── FADE ANIM ── */
    .fade-up {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.45s ease, transform 0.45s ease;
    }
    .fade-up.visible { opacity: 1; transform: translateY(0); }

    @media (max-width: 480px) {
        .route-card { flex-direction: column; gap: 16px; }
        .route-middle { flex-direction: row; padding: 0; }
        .route-line { width: 50px; }
        .page-title-row { flex-direction: column; }
    }
</style>
@endpush

@section('content')

@php
    $statusMap = [
        'en_transit' => ['label' => 'En transit',  'class' => 'badge-transit',   'emoji' => '🚚', 'step' => 2],
        'livre'      => ['label' => 'Livré',        'class' => 'badge-delivered', 'emoji' => '✅', 'step' => 3],
        'en_attente' => ['label' => 'En attente',   'class' => 'badge-pending',   'emoji' => '⏳', 'step' => 0],
        'retourne'   => ['label' => 'Retourné',     'class' => 'badge-returned',  'emoji' => '↩️', 'step' => 1],
    ];
    $st      = $statusMap[$colis->statut ?? 'en_attente'] ?? $statusMap['en_attente'];
    $curStep = $st['step'];
@endphp

<div class="show-container">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb fade-up">
        <a href="{{ route('home') }}">Accueil</a>
        <span class="sep">/</span>
        <a href="{{ route('colis.index') }}">Mes Colis</a>
        <span class="sep">/</span>
        <span class="current">Colis #{{ $colis->id }}</span>
    </nav>

    {{-- Page header --}}
    <div class="page-title-row fade-up" style="transition-delay:0.04s">
        <div>
            <div class="page-greeting">Détails</div>
            <h1 class="page-title">Colis #{{ $colis->id }}</h1>
        </div>
        <div style="display:flex; align-items:center; gap:10px; padding-top:8px;">
            <span class="badge {{ $st['class'] }}">
                <span class="badge-dot"></span>
                {{ $st['label'] }}
            </span>
        </div>
    </div>

    {{-- Route visuelle --}}
    <div class="route-card fade-up" style="transition-delay:0.08s">
        <div class="route-city">
            <div class="city-tag">Départ</div>
            <div class="city-name">{{ $colis->ville_depart }}</div>
        </div>

        <div class="route-middle">
            <div class="route-line" style="position:relative;">
                <span class="route-icon">{{ $st['emoji'] }}</span>
            </div>
            <div class="route-weight">{{ $colis->poids }} kg</div>
        </div>

        <div class="route-city">
            <div class="city-tag">Arrivée</div>
            <div class="city-name">{{ $colis->ville_arrivee }}</div>
        </div>
    </div>

    {{-- Tracking progress --}}
    <div class="tracking-card fade-up" style="transition-delay:0.12s">
        <div class="tracking-title">Suivi de livraison</div>

        <div class="progress-steps">

            @php
                $steps = [
                    ['icon' => '📋', 'label' => 'Enregistré',  'date' => $colis->created_at?->format('d M')],
                    ['icon' => '📦', 'label' => 'En entrepôt', 'date' => null],
                    ['icon' => '🚚', 'label' => 'En transit',  'date' => null],
                    ['icon' => '✅', 'label' => 'Livré',       'date' => $colis->statut === 'livre' ? $colis->updated_at?->format('d M') : null],
                ];
            @endphp

            @foreach($steps as $i => $step)
                <div class="progress-step
                    {{ $i < $curStep ? 'done' : ($i === $curStep ? 'active' : '') }}">
                    <div class="step-circle">
                        @if($i < $curStep)
                            {{-- Checkmark --}}
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.5">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                        @else
                            {{ $step['icon'] }}
                        @endif
                    </div>
                    <div class="step-label">{{ $step['label'] }}</div>
                    @if($step['date'])
                        <div class="step-date">{{ $step['date'] }}</div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>

    {{-- Info cards --}}
    <div class="info-grid fade-up" style="transition-delay:0.16s">

        {{-- Personnes --}}
        <div class="info-card">
            <div class="info-card-label">👥 Personnes</div>

            <div class="info-row">
                <span class="info-key">Expéditeur</span>
                <span class="info-val">{{ $colis->expediteur }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Destinataire</span>
                <span class="info-val">{{ $colis->destinataire }}</span>
            </div>
        </div>

        {{-- Colis --}}
        <div class="info-card">
            <div class="info-card-label">⚖️ Informations colis</div>

            <div class="info-row">
                <span class="info-key">Poids</span>
                <span class="info-val">{{ $colis->poids }} kg</span>
            </div>
            <div class="info-row">
                <span class="info-key">Référence</span>
                <span class="info-val" style="font-family:var(--font-head); font-size:0.75rem; color:var(--accent);">
                    YBL-{{ str_pad($colis->id, 6, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>

        {{-- Dates --}}
        <div class="info-card">
            <div class="info-card-label">📅 Dates</div>

            <div class="info-row">
                <span class="info-key">Enregistré le</span>
                <span class="info-val">
                    {{ $colis->created_at ? $colis->created_at->format('d M Y, H:i') : '—' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-key">Dernière mise à jour</span>
                <span class="info-val">
                    {{ $colis->updated_at ? $colis->updated_at->diffForHumans() : '—' }}
                </span>
            </div>
        </div>

        {{-- Statut --}}
        <div class="info-card">
            <div class="info-card-label">📊 Statut actuel</div>

            <div class="info-row">
                <span class="info-key">État</span>
                <span class="badge {{ $st['class'] }}" style="font-size:0.65rem;">
                    {{ $st['label'] }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-key">Trajet</span>
                <span class="info-val" style="font-size:0.8rem;">
                    {{ $colis->ville_depart }} → {{ $colis->ville_arrivee }}
                </span>
            </div>
        </div>

    </div>

    {{-- Action panel --}}
    <div class="action-panel fade-up" style="transition-delay:0.2s">
        <div class="action-left">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="opacity:0.4">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/>
            </svg>
            Dernière modification {{ $colis->updated_at ? $colis->updated_at->diffForHumans() : '—' }}
        </div>

        <div class="action-right">
            <a href="{{ route('colis.index') }}" class="btn-back">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Retour
            </a>

            <a href="{{ route('colis.edit', $colis) }}" class="btn-edit">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Modifier
            </a>

            <form method="POST" action="{{ route('colis.destroy', $colis) }}"
                  onsubmit="return confirm('Supprimer définitivement ce colis ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    </svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.04 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
</script>
@endpush
