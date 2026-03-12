@extends('layouts.app')

@section('title', 'Tableau de bord')

@push('styles')
<style>
    /* ── PAGE STYLES ── */
    .page-header {
        margin-bottom: 32px;
    }

    .page-greeting {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 8px;
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

    .page-title {
        font-family: var(--font-head);
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.1;
        color: var(--text);
    }

    /* ── KPI CARDS ── */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    @media (max-width: 1100px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 500px)  { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
        cursor: default;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        opacity: 0;
        transition: opacity 0.25s;
    }

    .kpi-card:hover {
        border-color: var(--border-hover);
        transform: translateY(-2px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.4);
    }

    .kpi-card:hover::before { opacity: 1; }

    .kpi-card.accent::before  { background: linear-gradient(90deg, var(--accent), transparent); }
    .kpi-card.blue::before    { background: linear-gradient(90deg, var(--blue-light), transparent); }
    .kpi-card.green::before   { background: linear-gradient(90deg, var(--green), transparent); }
    .kpi-card.purple::before  { background: linear-gradient(90deg, #a78bfa, transparent); }

    .kpi-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .kpi-icon {
        width: 42px; height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .kpi-icon.accent  { background: rgba(245,166,35,0.1);  border: 1px solid rgba(245,166,35,0.2); }
    .kpi-icon.blue    { background: rgba(96,165,250,0.1);   border: 1px solid rgba(96,165,250,0.2); }
    .kpi-icon.green   { background: rgba(34,197,94,0.1);    border: 1px solid rgba(34,197,94,0.2); }
    .kpi-icon.purple  { background: rgba(167,139,250,0.1);  border: 1px solid rgba(167,139,250,0.2); }

    .kpi-trend {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .kpi-trend.up   { background: rgba(34,197,94,0.12);  color: #4ade80; }
    .kpi-trend.down { background: rgba(248,113,113,0.12); color: #f87171; }
    .kpi-trend.neutral { background: rgba(255,255,255,0.06); color: var(--text-muted); }

    .kpi-value {
        font-family: var(--font-head);
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.04em;
        color: var(--text);
        line-height: 1;
        margin-bottom: 6px;
    }

    .kpi-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    /* ── BOTTOM GRID ── */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
    }

    @media (max-width: 1000px) { .bottom-grid { grid-template-columns: 1fr; } }

    /* ── CARD BASE ── */
    .card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-family: var(--font-head);
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text);
        letter-spacing: -0.01em;
    }

    .card-action {
        font-size: 0.75rem;
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.15s;
    }

    .card-action:hover { opacity: 0.75; }

    .card-body { padding: 24px; }

    /* ── PARCEL TABLE ── */
    .parcel-table {
        width: 100%;
        border-collapse: collapse;
    }

    .parcel-table th {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-dim);
        padding: 0 16px 14px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .parcel-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        font-size: 0.85rem;
        color: var(--text-muted);
        vertical-align: middle;
    }

    .parcel-table tr:last-child td { border-bottom: none; }

    .parcel-table tr:hover td {
        background: rgba(255,255,255,0.02);
        color: var(--text);
    }

    .parcel-ref {
        font-family: var(--font-head);
        font-size: 0.72rem;
        color: var(--text);
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .parcel-dest {
        font-size: 0.82rem;
        color: var(--text);
    }

    /* Status badges */
    .badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-transit   { background: rgba(37,99,235,0.15);  color: #93c5fd; border: 1px solid rgba(37,99,235,0.25); }
    .badge-delivered { background: rgba(34,197,94,0.12);  color: #4ade80; border: 1px solid rgba(34,197,94,0.2); }
    .badge-pending   { background: rgba(245,166,35,0.12); color: var(--accent); border: 1px solid rgba(245,166,35,0.25); }
    .badge-returned  { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.2); }

    /* Table action btn */
    .tbl-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 7px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
        font-family: var(--font-body);
    }

    .tbl-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--accent-dim);
    }

    /* ── QUICK ACTIONS ── */
    .quick-actions { display: flex; flex-direction: column; gap: 10px; }

    .qa-btn {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        border-radius: 11px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.02);
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .qa-btn:hover {
        border-color: rgba(245,166,35,0.3);
        background: var(--accent-dim);
        transform: translateX(3px);
    }

    .qa-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .qa-btn:hover .qa-icon {
        background: rgba(245,166,35,0.1);
        border-color: rgba(245,166,35,0.25);
    }

    .qa-text { flex: 1; }

    .qa-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }

    .qa-sub {
        font-size: 0.73rem;
        color: var(--text-muted);
    }

    .qa-arrow {
        color: var(--text-dim);
        transition: all 0.2s;
    }

    .qa-btn:hover .qa-arrow { color: var(--accent); transform: translateX(2px); }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }

    .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 16px;
        opacity: 0.4;
    }

    .empty-text {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    /* ── PROGRESS BAR ── */
    .mini-progress {
        background: rgba(255,255,255,0.06);
        border-radius: 999px;
        height: 4px;
        margin-top: 10px;
        overflow: hidden;
    }

    .mini-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--accent), #f0b84a);
        transition: width 1s ease;
    }

    /* ── FADE ANIMATION ── */
    .fade-up {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

    {{-- Page header --}}
    <div class="page-header fade-up">
        <div class="page-greeting">Bonjour</div>
        <h1 class="page-title">
            Bienvenue, {{ Auth::user()->name }} 
        </h1>
    </div>

    {{-- KPI Cards --}}
    <div class="kpi-grid">
        <div class="kpi-card accent fade-up" style="transition-delay:0.05s">
            <div class="kpi-top">
                <div class="kpi-icon accent">📦</div>
                <span class="kpi-trend up">↑ 12%</span>
            </div>
            <div class="kpi-value">{{ $totalColis ?? 0 }}</div>
            <div class="kpi-label">Colis total</div>
            <div class="mini-progress">
                <div class="mini-progress-fill" style="width:72%"></div>
            </div>
        </div>

        <div class="kpi-card blue fade-up" style="transition-delay:0.1s">
            <div class="kpi-top">
                <div class="kpi-icon blue">🚚</div>
                <span class="kpi-trend up">↑ 5%</span>
            </div>
            <div class="kpi-value">{{ $colisEnTransit ?? 0 }}</div>
            <div class="kpi-label">En transit</div>
            <div class="mini-progress">
                <div class="mini-progress-fill" style="width:45%; background: linear-gradient(90deg, #60a5fa, #93c5fd);"></div>
            </div>
        </div>

        <div class="kpi-card green fade-up" style="transition-delay:0.15s">
            <div class="kpi-top">
                <div class="kpi-icon green">✅</div>
                <span class="kpi-trend neutral">stable</span>
            </div>
            <div class="kpi-value">{{ $colisLivres ?? 0 }}</div>
            <div class="kpi-label">Livrés</div>
            <div class="mini-progress">
                <div class="mini-progress-fill" style="width:88%; background: linear-gradient(90deg, #22c55e, #4ade80);"></div>
            </div>
        </div>

        <div class="kpi-card purple fade-up" style="transition-delay:0.2s">
            <div class="kpi-top">
                <div class="kpi-icon purple">⏳</div>
                <span class="kpi-trend down">↓ 2%</span>
            </div>
            <div class="kpi-value">{{ $colisEnAttente ?? 0 }}</div>
            <div class="kpi-label">En attente</div>
            <div class="mini-progress">
                <div class="mini-progress-fill" style="width:30%; background: linear-gradient(90deg, #a78bfa, #c4b5fd);"></div>
            </div>
        </div>
    </div>

    {{-- Bottom grid --}}
    <div class="bottom-grid">

        {{-- Colis récents --}}
        <div class="card fade-up" style="transition-delay:0.25s">
            <div class="card-header">
                <span class="card-title">Derniers colis</span>
                <a href="{{ route('colis.index') }}" class="card-action">Voir tout →</a>
            </div>

            @if(isset($recentColis) && $recentColis->count() > 0)
                <table class="parcel-table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Destination</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentColis as $colis)
                        <tr>
                            <td>
                                <div class="parcel-ref">{{ $colis->reference ?? 'YBL-' . str_pad($colis->id, 6, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td>
                                <div class="parcel-dest">{{ $colis->destination ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'en_transit'  => ['label' => 'En transit',  'class' => 'badge-transit'],
                                        'livre'       => ['label' => 'Livré',        'class' => 'badge-delivered'],
                                        'en_attente'  => ['label' => 'En attente',   'class' => 'badge-pending'],
                                        'retourne'    => ['label' => 'Retourné',     'class' => 'badge-returned'],
                                    ];
                                    $st = $statusMap[$colis->statut] ?? ['label' => $colis->statut ?? '—', 'class' => 'badge-pending'];
                                @endphp
                                <span class="badge {{ $st['class'] }}">{{ $st['label'] }}</span>
                            </td>
                            <td>{{ $colis->created_at ? $colis->created_at->format('d M Y') : '—' }}</td>
                            <td>
                                <a href="{{ route('colis.show', $colis) }}" class="tbl-btn">
                                    Voir
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M7 17L17 7M17 7H7M17 7v10"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                {{-- Empty state --}}
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <p class="empty-text">Aucun colis enregistré pour l'instant.</p>
                    <a href="{{ route('colis.create') }}" class="btn-primary" style="font-size:0.78rem; padding:10px 20px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        Expédier mon premier colis
                    </a>
                </div>
            @endif
        </div>

        {{-- Sidebar right --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- Quick actions --}}
            <div class="card fade-up" style="transition-delay:0.3s">
                <div class="card-header">
                    <span class="card-title">Actions rapides</span>
                </div>
                <div class="card-body">
                    <div class="quick-actions">

                        <a href="{{ route('colis.create') }}" class="qa-btn">
                            <div class="qa-icon">📦</div>
                            <div class="qa-text">
                                <div class="qa-label">Expédier un colis</div>
                                <div class="qa-sub">Créer une nouvelle expédition</div>
                            </div>
                            <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('colis.index') }}" class="qa-btn">
                            <div class="qa-icon">🔍</div>
                            <div class="qa-text">
                                <div class="qa-label">Suivre un colis</div>
                                <div class="qa-sub">Voir l'état de vos envois</div>
                            </div>
                            <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="qa-btn">
                            <div class="qa-icon">⚙️</div>
                            <div class="qa-text">
                                <div class="qa-label">Mon profil</div>
                                <div class="qa-sub">Paramètres et informations</div>
                            </div>
                            <svg class="qa-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>

                    </div>
                </div>
            </div>

            {{-- Info card --}}
            <div class="card fade-up" style="transition-delay:0.35s; background: linear-gradient(135deg, rgba(245,166,35,0.06), rgba(37,99,235,0.06)); border-color: rgba(245,166,35,0.15);">
                <div class="card-body">
                    <div style="font-size:1.5rem; margin-bottom:12px;">🇨🇲</div>
                    <div style="font-family:var(--font-head); font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:8px; letter-spacing:-0.01em;">
                        Livraison partout au Cameroun
                    </div>
                    <p style="font-size:0.78rem; color:var(--text-muted); line-height:1.7; margin-bottom:16px;">
                        Douala, Yaoundé, Bafoussam, Garoua et plus de 40 villes desservies par notre réseau de confiance.
                    </p>
                    <a href="{{ route('about') }}" style="font-size:0.75rem; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
                        En savoir plus
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Scroll reveal
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.05 });

    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));

    // Animate KPI counters
    document.querySelectorAll('.kpi-value').forEach(el => {
        const target = parseInt(el.textContent.trim(), 10);
        if (isNaN(target) || target === 0) return;
        let count = 0;
        const step = Math.max(1, Math.floor(target / 30));
        const timer = setInterval(() => {
            count = Math.min(count + step, target);
            el.textContent = count;
            if (count >= target) clearInterval(timer);
        }, 30);
    });
</script>
@endpush
