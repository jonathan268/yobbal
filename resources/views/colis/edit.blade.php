@extends('layouts.app')

@section('title', 'Modifier le Colis')

@push('styles')
<style>
    .form-container {
        max-width: 680px;
        margin: 0 auto;
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
        margin-bottom: 28px;
    }

    /* ── ID BADGE ── */
    .colis-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 8px;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        font-family: var(--font-head);
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 20px;
        letter-spacing: 0.04em;
    }

    .colis-id-badge .id-num { color: var(--accent); }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
    }

    .form-card-header {
        padding: 22px 32px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .form-card-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(96,165,250,0.1);
        border: 1px solid rgba(96,165,250,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .form-card-title {
        font-family: var(--font-head);
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text);
        letter-spacing: -0.01em;
    }

    .form-card-sub {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* ── CURRENT INFO PREVIEW ── */
    .current-preview {
        margin: 0 32px 24px;
        padding: 14px 18px;
        background: rgba(96,165,250,0.05);
        border: 1px solid rgba(96,165,250,0.15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    .current-preview .route-strong {
        font-weight: 600;
        color: var(--text);
        font-size: 0.88rem;
    }

    .route-sep { color: #60a5fa; font-weight: 700; }

    .form-body { padding: 0 32px 32px; padding-top: 8px; }

    .form-section { margin-bottom: 28px; }

    .section-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.13em;
        text-transform: uppercase;
        color: var(--text-dim);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

    .form-row { display: grid; gap: 16px; }
    .form-row.two { grid-template-columns: 1fr 1fr; }
    .form-row.one { grid-template-columns: 1fr; }

    @media (max-width: 500px) {
        .form-row.two { grid-template-columns: 1fr; }
        .form-body { padding: 0 18px 24px; }
        .form-card-header { padding: 18px; }
        .current-preview { margin: 0 18px 20px; }
        .form-footer { padding: 18px; }
    }

    .field { display: flex; flex-direction: column; gap: 8px; }

    label.field-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    label.field-label .required { color: var(--accent); font-size: 0.7rem; }

    .field-input {
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 13px 16px;
        color: var(--text);
        font-family: var(--font-body);
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        width: 100%;
    }

    .field-input::placeholder { color: var(--text-dim); font-size: 0.85rem; }

    .field-input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.12);
        background: rgba(255,255,255,0.06);
    }

    .field-input:hover:not(:focus) {
        border-color: var(--border-hover);
        background: rgba(255,255,255,0.05);
    }

    .field-input.is-error { border-color: #f87171; }
    .field-input.is-error:focus { box-shadow: 0 0 0 3px rgba(248,113,113,0.12); }

    .field-error {
        font-size: 0.75rem;
        color: #f87171;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Select custom */
    select.field-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' stroke='%238892B0' stroke-width='2' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        padding-right: 40px;
        cursor: pointer;
    }

    select.field-input option { background: #0D1629; color: var(--text); }

    /* ── FORM FOOTER ── */
    .form-footer {
        padding: 22px 32px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .footer-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .danger-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        border-radius: 9px;
        border: 1px solid rgba(248,113,113,0.25);
        background: rgba(248,113,113,0.06);
        color: #f87171;
        font-family: var(--font-head);
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        letter-spacing: 0.02em;
        transition: all 0.18s;
    }

    .danger-btn:hover {
        background: rgba(248,113,113,0.12);
        border-color: rgba(248,113,113,0.4);
        transform: translateY(-1px);
    }

    .footer-right { display: flex; align-items: center; gap: 10px; }

    .btn-cancel {
        padding: 10px 20px;
        border-radius: 9px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        font-family: var(--font-head);
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.18s;
        letter-spacing: 0.02em;
    }

    .btn-cancel:hover {
        border-color: var(--border-hover);
        color: var(--text);
        background: rgba(255,255,255,0.04);
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 24px;
        border-radius: 9px;
        border: 1px solid #60a5fa;
        background: #2563eb;
        color: #fff;
        font-family: var(--font-head);
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.02em;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: #3b82f6;
        box-shadow: 0 0 24px rgba(59,130,246,0.3);
        transform: translateY(-1px);
    }

    /* ── FADE ANIM ── */
    .fade-up {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.45s ease, transform 0.45s ease;
    }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

<div class="form-container">

    {{-- Header --}}
    <div class="fade-up">
        <div class="page-greeting">Modification</div>
        <h1 class="page-title">Modifier le colis</h1>
    </div>

    {{-- Colis ID badge --}}
    <div class="colis-id-badge fade-up" style="transition-delay:0.04s">
        ✏️ &nbsp;Colis <span class="id-num">#{{ $colis->id }}</span>
        &nbsp;·&nbsp;
        Créé le {{ $colis->created_at ? $colis->created_at->format('d M Y') : '—' }}
    </div>

    {{-- Form card --}}
    <div class="form-card fade-up" style="transition-delay:0.08s">

        <div class="form-card-header">
            <div class="form-card-icon">✏️</div>
            <div>
                <div class="form-card-title">Modifier les informations</div>
                <div class="form-card-sub">Les champs modifiés écraseront les données actuelles</div>
            </div>
        </div>

        {{-- Current route preview --}}
        <div class="current-preview">
            <span>📍 Trajet actuel :</span>
            <span class="route-strong">{{ $colis->ville_depart }}</span>
            <span class="route-sep">→</span>
            <span class="route-strong">{{ $colis->ville_arrivee }}</span>
            <span style="margin-left:auto; font-size:0.75rem;">
                {{ $colis->poids }} kg
            </span>
        </div>

        <form action="{{ route('colis.update', $colis) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-body">

                {{-- Section : Personnes --}}
                <div class="form-section">
                    <div class="section-label">👥 Expéditeur & Destinataire</div>

                    <div class="form-row two">
                        <div class="field">
                            <label class="field-label" for="expediteur">
                                Expéditeur <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="expediteur"
                                name="expediteur"
                                value="{{ old('expediteur', $colis->expediteur) }}"
                                class="field-input {{ $errors->has('expediteur') ? 'is-error' : '' }}"
                                placeholder="Nom de l'expéditeur"
                                required
                            >
                            @error('expediteur')
                                <span class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="destinataire">
                                Destinataire <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="destinataire"
                                name="destinataire"
                                value="{{ old('destinataire', $colis->destinataire) }}"
                                class="field-input {{ $errors->has('destinataire') ? 'is-error' : '' }}"
                                placeholder="Nom du destinataire"
                                required
                            >
                            @error('destinataire')
                                <span class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section : Trajet --}}
                <div class="form-section">
                    <div class="section-label">📍 Trajet</div>

                    <div class="form-row two">
                        <div class="field">
                            <label class="field-label" for="ville_depart">
                                Ville de départ <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="ville_depart"
                                name="ville_depart"
                                value="{{ old('ville_depart', $colis->ville_depart) }}"
                                class="field-input {{ $errors->has('ville_depart') ? 'is-error' : '' }}"
                                placeholder="Ex : Douala"
                                required
                            >
                            @error('ville_depart')
                                <span class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="ville_arrivee">
                                Ville d'arrivée <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="ville_arrivee"
                                name="ville_arrivee"
                                value="{{ old('ville_arrivee', $colis->ville_arrivee) }}"
                                class="field-input {{ $errors->has('ville_arrivee') ? 'is-error' : '' }}"
                                placeholder="Ex : Yaoundé"
                                required
                            >
                            @error('ville_arrivee')
                                <span class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section : Détails --}}
                <div class="form-section" style="margin-bottom:0">
                    <div class="section-label">⚖️ Détails du colis</div>

                    <div class="form-row two">
                        <div class="field">
                            <label class="field-label" for="poids">
                                Poids (kg) <span class="required">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                id="poids"
                                name="poids"
                                value="{{ old('poids', $colis->poids) }}"
                                class="field-input {{ $errors->has('poids') ? 'is-error' : '' }}"
                                placeholder="0.0"
                                required
                            >
                            @error('poids')
                                <span class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="statut">Statut</label>
                            <select
                                id="statut"
                                name="statut"
                                class="field-input {{ $errors->has('statut') ? 'is-error' : '' }}"
                            >
                                <option value="en_attente"  {{ old('statut', $colis->statut) === 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
                                <option value="en_transit"  {{ old('statut', $colis->statut) === 'en_transit'  ? 'selected' : '' }}>🚚 En transit</option>
                                <option value="livre"        {{ old('statut', $colis->statut) === 'livre'       ? 'selected' : '' }}>✅ Livré</option>
                                <option value="retourne"     {{ old('statut', $colis->statut) === 'retourne'    ? 'selected' : '' }}>↩️ Retourné</option>
                            </select>
                            @error('statut')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>{{-- /form-body --}}

            <div class="form-footer">
                {{-- Left : delete --}}
                <div class="footer-left">
                    <form method="POST" action="{{ route('colis.destroy', $colis) }}"
                          onsubmit="return confirm('Supprimer définitivement ce colis ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="danger-btn">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6"/>
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>

                {{-- Right : cancel + save --}}
                <div class="footer-right">
                    <a href="{{ route('colis.index') }}" class="btn-cancel">Annuler</a>
                    <button type="submit" class="btn-submit">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.05 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
</script>
@endpush
