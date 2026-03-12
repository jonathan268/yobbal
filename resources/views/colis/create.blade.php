@extends('layouts.app')

@section('title', 'Nouveau Colis')

@push('styles')
<style>
    /* ── FORM WRAPPER ── */
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

    /* ── FORM CARD ── */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
    }

    .form-card-header {
        padding: 24px 32px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .form-card-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(245,166,35,0.1);
        border: 1px solid rgba(245,166,35,0.2);
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

    .form-body { padding: 32px; }

    /* ── SECTION LABEL ── */
    .form-section {
        margin-bottom: 28px;
    }

    .section-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.13em;
        text-transform: uppercase;
        color: var(--text-dim);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── FORM ROW & FIELDS ── */
    .form-row {
        display: grid;
        gap: 16px;
    }

    .form-row.two { grid-template-columns: 1fr 1fr; }
    .form-row.one { grid-template-columns: 1fr; }

    @media (max-width: 500px) {
        .form-row.two { grid-template-columns: 1fr; }
        .form-body { padding: 24px 18px; }
        .form-card-header { padding: 20px 18px; }
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    label.field-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    label.field-label .required {
        color: var(--accent);
        font-size: 0.7rem;
    }

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
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245,166,35,0.12);
        background: rgba(255,255,255,0.06);
    }

    .field-input:hover:not(:focus) {
        border-color: var(--border-hover);
        background: rgba(255,255,255,0.05);
    }

    /* Error state */
    .field-input.is-error { border-color: #f87171; }
    .field-input.is-error:focus { box-shadow: 0 0 0 3px rgba(248,113,113,0.12); }

    .field-error {
        font-size: 0.75rem;
        color: #f87171;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Route visuel */
    .route-connector {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .route-connector .field { flex: 1; }

    .route-arrow-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 28px 12px 0;
        color: var(--accent);
        flex-shrink: 0;
    }

    /* ── VALIDATION ERRORS ── */
    .errors-box {
        background: rgba(248,113,113,0.08);
        border: 1px solid rgba(248,113,113,0.25);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }

    .errors-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #f87171;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .errors-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .errors-list li {
        font-size: 0.8rem;
        color: #fca5a5;
        padding-left: 14px;
        position: relative;
    }

    .errors-list li::before {
        content: '·';
        position: absolute;
        left: 4px;
        color: #f87171;
        font-size: 1.2rem;
        line-height: 0.9;
    }

    /* ── FORM FOOTER ── */
    .form-footer {
        padding: 24px 32px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 500px) {
        .form-footer { padding: 18px; }
    }

    .btn-cancel {
        padding: 11px 22px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        font-family: var(--font-head);
        font-size: 0.75rem;
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
        padding: 11px 26px;
        border-radius: 10px;
        border: 1px solid var(--accent);
        background: var(--accent);
        color: #000;
        font-family: var(--font-head);
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.02em;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: #f0b84a;
        box-shadow: 0 0 28px rgba(245,166,35,0.3);
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
        <div class="page-greeting">Expédition</div>
        <h1 class="page-title">Enregistrer un colis</h1>
    </div>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="errors-box fade-up">
            <div class="errors-title">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
                </svg>
                {{ $errors->count() }} erreur{{ $errors->count() > 1 ? 's' : '' }} à corriger
            </div>
            <ul class="errors-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form card --}}
    <div class="form-card fade-up" style="transition-delay:0.08s">

        <div class="form-card-header">
            <div class="form-card-icon">📦</div>
            <div>
                <div class="form-card-title">Nouveau colis</div>
                <div class="form-card-sub">Remplissez les informations d'expédition ci-dessous</div>
            </div>
        </div>

        <form action="{{ route('colis.store') }}" method="POST">
            @csrf

            <div class="form-body">

                {{-- Section : Personnes --}}
                <div class="form-section">
                    <div class="section-label">
                        👥 Expéditeur & Destinataire
                    </div>

                    <div class="form-row two">
                        <div class="field">
                            <label class="field-label" for="expediteur">
                                Expéditeur <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="expediteur"
                                name="expediteur"
                                value="{{ old('expediteur') }}"
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
                                value="{{ old('destinataire') }}"
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
                    <div class="section-label">
                        📍 Trajet
                    </div>

                    <div class="form-row two">
                        <div class="field">
                            <label class="field-label" for="ville_depart">
                                Ville de départ <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="ville_depart"
                                name="ville_depart"
                                value="{{ old('ville_depart') }}"
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
                                value="{{ old('ville_arrivee') }}"
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

                {{-- Section : Colis --}}
                <div class="form-section" style="margin-bottom:0">
                    <div class="section-label">
                        ⚖️ Détails du colis
                    </div>

                    <div class="form-row two">
                        <div class="field">
                            <label class="field-label" for="poids">
                                Poids <span class="required">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                id="poids"
                                name="poids"
                                value="{{ old('poids') }}"
                                class="field-input {{ $errors->has('poids') ? 'is-error' : '' }}"
                                placeholder="0.0 kg"
                                required
                            >
                            @error('poids')
                                <span class="field-error">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Champ statut si applicable --}}
                        @if(isset($statuts))
                        <div class="field">
                            <label class="field-label" for="statut">Statut initial</label>
                            <select id="statut" name="statut" class="field-input">
                                @foreach($statuts as $val => $label)
                                    <option value="{{ $val }}" {{ old('statut') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </div>

            </div>{{-- /form-body --}}

            <div class="form-footer">
                <a href="{{ route('colis.index') }}" class="btn-cancel">Annuler</a>
                <button type="submit" class="btn-submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    Valider l'enregistrement
                </button>
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
