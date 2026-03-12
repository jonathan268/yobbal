@extends('layouts.app')

@section('title', 'Mes Colis')

@push('styles')
<style>
    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }

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

    .page-title {
        font-family: var(--font-head);
        font-size: clamp(1.4rem, 3vw, 1.9rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--text);
        line-height: 1.1;
    }

    /* ── COUNTER BADGE ── */
    .count-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 999px;
        background: rgba(245,166,35,0.1);
        border: 1px solid rgba(245,166,35,0.25);
        font-family: var(--font-head);
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--accent);
        white-space: nowrap;
    }

    .count-badge .count-num {
        font-size: 1rem;
        letter-spacing: -0.02em;
    }

    /* ── FILTER BAR ── */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding: 16px 20px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }

    .filter-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-dim);
        margin-right: 4px;
        white-space: nowrap;
    }

    .filter-chip {
        padding: 5px 14px;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        font-size: 0.78rem;
        font-weight: 500;
        cursor: pointer;
        font-family: var(--font-body);
        transition: all 0.15s ease;
        text-decoration: none;
        display: inline-block;
    }

    .filter-chip:hover,
    .filter-chip.active {
        border-color: var(--accent);
        color: var(--accent);
        background: rgba(245,166,35,0.08);
    }

    .filter-divider {
        width: 1px; height: 20px;
        background: var(--border);
        flex-shrink: 0;
    }

    .search-input {
        flex: 1;
        min-width: 180px;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 7px 14px;
        color: var(--text);
        font-family: var(--font-body);
        font-size: 0.82rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-input::placeholder { color: var(--text-dim); }

    .search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245,166,35,0.12);
    }

    /* ── COLIS LIST ── */
    .colis-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .colis-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.22s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
    }

    .colis-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--border);
        transition: background 0.2s;
    }

    .colis-card:hover {
        border-color: var(--border-hover);
        transform: translateX(3px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.35);
    }

    .colis-card:hover::before { background: var(--accent); }

    /* Status left accent */
    .colis-card.transit::before  { background: #60a5fa; }
    .colis-card.livre::before    { background: #4ade80; }
    .colis-card.attente::before  { background: var(--accent); }
    .colis-card.retourne::before { background: #f87171; }

    /* Icon */
    .colis-icon {
        width: 48px; height: 48px;
        border-radius: 13px;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    /* Main info */
    .colis-main { flex: 1; min-width: 0; }

    .colis-route {
        font-family: var(--font-head);
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text);
        letter-spacing: -0.01em;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .route-arrow {
        color: var(--accent);
        font-size: 0.8rem;
    }

    .colis-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .meta-item svg { opacity: 0.6; }

    /* Right side */
    .colis-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
        flex-shrink: 0;
    }

    /* Badge statut */
    .badge {
        padding: 4px 11px;
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

    /* Action buttons */
    .colis-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .act-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s;
        font-size: 0.8rem;
    }

    .act-btn:hover         { border-color: var(--accent); color: var(--accent); background: rgba(245,166,35,0.08); }
    .act-btn.danger:hover  { border-color: #f87171; color: #f87171; background: rgba(248,113,113,0.08); }

    /* ── EMPTY STATE ── */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 80px 24px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }

    .empty-emoji { font-size: 3rem; margin-bottom: 16px; opacity: 0.35; }

    .empty-title {
        font-family: var(--font-head);
        font-size: 1rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .empty-sub {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 28px;
        max-width: 300px;
        line-height: 1.7;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 11px;
        border: 1px solid var(--accent);
        background: var(--accent);
        color: #000;
        font-family: var(--font-head);
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        letter-spacing: 0.02em;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #f0b84a;
        box-shadow: 0 0 24px var(--accent-glow);
        transform: translateY(-1px);
    }

    /* ── PAGINATION ── */
    .pagination-wrap {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    /* ── FADE ANIM ── */
    .fade-up {
        opacity: 0;
        transform: translateY(14px);
        transition: opacity 0.45s ease, transform 0.45s ease;
    }
    .fade-up.visible { opacity: 1; transform: translateY(0); }

    @media (max-width: 640px) {
        .colis-card { flex-direction: column; align-items: flex-start; }
        .colis-right { align-items: flex-start; flex-direction: row; flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

    {{-- Header --}}
    <div class="page-header fade-up">
        <div>
            <div class="page-greeting">Gestion</div>
            <h1 class="page-title">Mes Colis</h1>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <span class="count-badge">
                📦 <span class="count-num">{{ $colis->count() }}</span> colis enregistré{{ $colis->count() > 1 ? 's' : '' }}
            </span>
            <a href="{{ route('colis.create') }}" class="btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Nouveau colis
            </a>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar fade-up" style="transition-delay:0.05s">
        <span class="filter-label">Filtrer</span>
        <a href="{{ request()->fullUrlWithQuery(['statut' => null]) }}" class="filter-chip {{ !request('statut') ? 'active' : '' }}">Tous</a>
        <a href="{{ request()->fullUrlWithQuery(['statut' => 'en_attente']) }}" class="filter-chip {{ request('statut') === 'en_attente' ? 'active' : '' }}">En attente</a>
        <a href="{{ request()->fullUrlWithQuery(['statut' => 'en_transit']) }}" class="filter-chip {{ request('statut') === 'en_transit' ? 'active' : '' }}">En transit</a>
        <a href="{{ request()->fullUrlWithQuery(['statut' => 'livre']) }}" class="filter-chip {{ request('statut') === 'livre' ? 'active' : '' }}">Livrés</a>
        <a href="{{ request()->fullUrlWithQuery(['statut' => 'retourne']) }}" class="filter-chip {{ request('statut') === 'retourne' ? 'active' : '' }}">Retournés</a>
        <div class="filter-divider"></div>
        <input
            type="text"
            class="search-input"
            placeholder="Rechercher par nom, ville..."
            id="searchInput"
            oninput="filterColis(this.value)"
        >
    </div>

    {{-- List --}}
    @if ($colis->count() > 0)
        <div class="colis-list" id="colisList">
            @foreach ($colis as $c)
                @php
                    $statusMap = [
                        'en_transit' => ['label' => 'En transit',  'class' => 'badge-transit',   'card' => 'transit',  'emoji' => '🚚'],
                        'livre'      => ['label' => 'Livré',        'class' => 'badge-delivered',  'card' => 'livre',    'emoji' => '✅'],
                        'en_attente' => ['label' => 'En attente',   'class' => 'badge-pending',    'card' => 'attente',  'emoji' => '⏳'],
                        'retourne'   => ['label' => 'Retourné',     'class' => 'badge-returned',   'card' => 'retourne', 'emoji' => '↩️'],
                    ];
                    $st = $statusMap[$c->statut ?? 'en_attente'] ?? ['label' => $c->statut ?? '—', 'class' => 'badge-pending', 'card' => 'attente', 'emoji' => '📦'];
                @endphp

                <div class="colis-card {{ $st['card'] }} fade-up" style="transition-delay:{{ $loop->index * 0.04 }}s"
                     data-search="{{ strtolower($c->expediteur . ' ' . $c->destinataire . ' ' . $c->ville_depart . ' ' . $c->ville_arrivee) }}">

                    <div class="colis-icon">{{ $st['emoji'] }}</div>

                    <div class="colis-main">
                        <div class="colis-route">
                            <span>{{ $c->expediteur }}</span>
                            <span class="route-arrow">→</span>
                            <span>{{ $c->destinataire }}</span>
                        </div>
                        <div class="colis-meta">
                            <span class="meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s-8-4.5-8-11.8A8 8 0 0112 2a8 8 0 018 8.2c0 7.3-8 11.8-8 11.8z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                {{ $c->ville_depart }} → {{ $c->ville_arrivee }}
                            </span>
                            <span class="meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M3 12h18M3 18h18"/>
                                </svg>
                                {{ $c->poids }} kg
                            </span>
                            @if($c->created_at)
                            <span class="meta-item">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                                </svg>
                                {{ $c->created_at->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="colis-right">
                        <span class="badge {{ $st['class'] }}">{{ $st['label'] }}</span>
                        <div class="colis-actions">
                            {{-- Voir --}}
                            <a href="{{ route('colis.show', $c) }}" class="act-btn" title="Voir les détails">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                            {{-- Modifier --}}
                            <a href="{{ route('colis.edit', $c) }}" class="act-btn" title="Modifier">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            {{-- Supprimer --}}
                            <form method="POST" action="{{ route('colis.destroy', $c) }}"
                                  onsubmit="return confirm('Supprimer ce colis définitivement ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="act-btn danger" title="Supprimer">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination si tu utilises paginate() dans le controller --}}
        @if (method_exists($colis, 'links'))
            <div class="pagination-wrap">
                {{ $colis->links() }}
            </div>
        @endif

    @else
        <div class="empty-state fade-up" style="transition-delay:0.1s">
            <div class="empty-emoji">📭</div>
            <div class="empty-title">Aucun colis enregistré</div>
            <p class="empty-sub">
                Votre espace est vide pour l'instant. Commencez dès maintenant en expédiant votre premier colis.
            </p>
            <a href="{{ route('colis.create') }}" class="btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Expédier un colis
            </a>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    // Scroll reveal
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.04 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));

    // Client-side search filter
    function filterColis(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('#colisList .colis-card').forEach(card => {
            const data = card.dataset.search || '';
            card.style.display = !q || data.includes(q) ? '' : 'none';
        });
    }
</script>
@endpush
