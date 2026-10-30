@extends('layouts.admin')

@section('title', $heading)
@section('kicker', 'Editor Keahlian')

@php
    $level = (int) old('level', $skill->level ?? 80);
    $level = max(0, min(100, $level));
@endphp

@section('content')
    <div class="admin-page-head">
        <div>
            <a class="admin-back-link" href="{{ route('admin.skills.index') }}">
                {!! \App\Support\Icons::svg('arrow-left') !!}
                Kembali ke daftar keahlian
            </a>
            <h2>{{ $heading }}</h2>
            <p>{{ $subheading }}</p>
        </div>
        <div class="admin-page-actions">
            <a class="admin-button admin-button--ghost" href="{{ route('admin.skills.index') }}">Batal</a>
            <button class="admin-button admin-button--gold" type="submit" form="skillForm">
                {!! \App\Support\Icons::svg('check') !!}
                Simpan keahlian
            </button>
        </div>
    </div>

    <form class="admin-form" id="skillForm" method="POST" action="{{ $action }}">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="admin-editor-grid">
            <div class="admin-editor-main">
                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Detail keahlian</span>
                            <h3>Informasi kemampuan</h3>
                        </div>
                    </div>

                    <div class="admin-form-grid">
                        <div class="admin-field">
                            <label for="name">Nama keahlian</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $skill->name) }}" placeholder="Contoh: Analisis Data" required maxlength="120">
                            @error('name')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="name_en">Nama keahlian (English)</label>
                            <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $skill->name_en) }}" maxlength="120">
                            @error('name_en')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="category_id">Kategori</label>
                            <input type="text" id="category_id" name="category" value="{{ old('category', $skill->category) }}" placeholder="Data, Desain, Web…" maxlength="100">
                            @error('category')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="category_en">Kategori (English)</label>
                            <input type="text" id="category_en" name="category_en" value="{{ old('category_en', $skill->category_en) }}" maxlength="100">
                            @error('category_en')<small>{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="admin-field admin-field--range" style="margin-top: 22px">
                        <label for="level">Tingkat penguasaan <span>{{ $level }}%</span></label>
                        <div class="admin-range-field">
                            <input type="range" min="0" max="100" step="1" value="{{ $level }}" data-range-for="level" aria-label="Tingkat penguasaan">
                            <div class="admin-range-value">
                                <input type="number" id="level" name="level" value="{{ $level }}" min="0" max="100" required>
                                <span>%</span>
                            </div>
                        </div>
                        @error('level')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="sort_order">Urutan tampil <span>Angka kecil lebih dulu</span></label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $skill->sort_order) }}" min="0" max="9999">
                        @error('sort_order')<small>{{ $message }}</small>@enderror
                    </div>
                </section>
            </div>

            <aside class="admin-editor-side">
                <div class="admin-card admin-sticky-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Pratinjau</span>
                            <h3>Tampilan publik</h3>
                        </div>
                    </div>

                    <div class="admin-skill-preview">
                        <div class="admin-skill-preview__head">
                            <strong data-skill-preview-name>{{ $skill->name ?: 'Nama Keahlian' }}</strong>
                            <span data-skill-preview-value>{{ $level }}%</span>
                        </div>
                        <span class="admin-skill-preview__category" data-skill-preview-category>{{ $skill->category ?: 'Kategori' }}</span>
                        <div class="admin-skill-preview__bar">
                            <span data-skill-preview-bar style="width: {{ $level }}%"></span>
                        </div>
                    </div>

                    <div class="admin-divider"></div>

                    <div class="admin-editor-actions">
                        <button class="admin-button admin-button--gold admin-button--full" type="submit">
                            {!! \App\Support\Icons::svg('check') !!}
                            {{ $method === 'POST' ? 'Simpan keahlian' : 'Perbarui keahlian' }}
                        </button>
                        <a class="admin-button admin-button--ghost admin-button--full" href="{{ route('admin.skills.index') }}">Batal</a>
                    </div>

                    <p class="admin-muted" style="margin-top: 16px">
                        Urutan dan tingkat penguasaan langsung memengaruhi tampilan bagian keahlian.
                    </p>
                </div>
            </aside>
        </div>
    </form>
@endsection
