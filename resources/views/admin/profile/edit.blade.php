@extends('layouts.admin')

@section('title', 'Profil Situs')
@section('kicker', 'Identitas Publik')

@php
    $photo = $profile->photoUrl();

    $socialValues = [];

    foreach (($profile->socials ?: []) as $key => $entry) {
        if (is_string($entry)) {
            $socialValues[strtolower($key)] = $entry;

            continue;
        }

        $network = strtolower($entry['network'] ?? $entry['label'] ?? $key);
        $socialValues[$network] = $entry['url'] ?? '';
    }
@endphp

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-kicker">Pengaturan situs</span>
            <h2>Profil situs</h2>
            <p>Perbarui identitas, cerita, statistik, dan tautan sosial yang tampil pada halaman publik.</p>
        </div>
        <div class="admin-page-actions">
            <a class="admin-button admin-button--ghost" href="{{ route('home') }}" target="_blank" rel="noopener">
                {!! \App\Support\Icons::svg('external') !!}
                Lihat situs
            </a>
            <button class="admin-button admin-button--gold" type="submit" form="profileForm">
                {!! \App\Support\Icons::svg('check') !!}
                Simpan perubahan
            </button>
        </div>
    </div>

    <form class="admin-form" id="profileForm" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="admin-editor-grid">
            <div class="admin-editor-main">
                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Identitas</span>
                            <h3>Informasi utama</h3>
                        </div>
                        <span class="admin-card__hint">Tampil di hero &amp; footer</span>
                    </div>

                    <div class="admin-profile-media">
                        <span class="admin-profile-photo">
                            <img src="{{ $photo ?: asset('assets/images/portrait-placeholder.svg') }}" alt="{{ $profile->name }}">
                        </span>

                        <div class="admin-profile-media__info">
                            <strong>{{ $profile->name }}</strong>
                            <small>{{ $profile->role ?: 'Peran belum diisi' }}</small>
                            <small>{{ $profile->location ?: 'Lokasi belum diisi' }}</small>
                        </div>
                    </div>

                    <div class="admin-form-grid">
                        <div class="admin-field">
                            <label for="name">Nama lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $profile->name) }}" placeholder="Nama yang tampil di situs" required maxlength="120">
                            @error('name')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="role">Peran / profesi</label>
                            <input type="text" id="role" name="role" value="{{ old('role', $profile->role) }}" placeholder="Web Developer & Visual Designer" maxlength="160">
                            @error('role')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="role_en">Peran / profesi (English)</label>
                            <input type="text" id="role_en" name="role_en" value="{{ old('role_en', $profile->role_en) }}" maxlength="160">
                            @error('role_en')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="email">Email publik</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $profile->email) }}" placeholder="nama@email.com" maxlength="160">
                            @error('email')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="phone">Telepon</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="+62 812 0000 0000" maxlength="60">
                            @error('phone')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="location">Lokasi</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $profile->location) }}" placeholder="Jakarta, Indonesia" maxlength="160">
                            @error('location')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="location_en">Lokasi (English)</label>
                            <input type="text" id="location_en" name="location_en" value="{{ old('location_en', $profile->location_en) }}" maxlength="160">
                            @error('location_en')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="availability">Status ketersediaan</label>
                            <input type="text" id="availability" name="availability" value="{{ old('availability', $profile->availability) }}" placeholder="Terbuka untuk proyek baru" maxlength="120">
                            @error('availability')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="availability_en">Status ketersediaan (English)</label>
                            <input type="text" id="availability_en" name="availability_en" value="{{ old('availability_en', $profile->availability_en) }}" maxlength="120">
                            @error('availability_en')<small>{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="admin-field" style="margin-top: 18px">
                        <label for="tagline">Tagline <span>Maks. 300 karakter</span></label>
                        <textarea id="tagline" name="tagline" rows="2" maxlength="300" placeholder="Ringkasan singkat yang tampil di bawah nama.">{{ old('tagline', $profile->tagline) }}</textarea>
                        @error('tagline')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="tagline_en">Tagline (English) <span>Max. 300 characters</span></label>
                        <textarea id="tagline_en" name="tagline_en" rows="2" maxlength="300">{{ old('tagline_en', $profile->tagline_en) }}</textarea>
                        @error('tagline_en')<small>{{ $message }}</small>@enderror
                    </div>
                </section>

                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Tentang</span>
                            <h3>Cerita &amp; fokus</h3>
                        </div>
                    </div>

                    <div class="admin-field">
                        <label for="about_lead">Paragraf pembuka <span>Maks. 600 karakter</span></label>
                        <textarea id="about_lead" name="about_lead" rows="3" maxlength="600" placeholder="Kalimat pembuka bagian tentang.">{{ old('about_lead', $profile->about_lead) }}</textarea>
                        @error('about_lead')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="about_lead_en">Paragraf pembuka (English) <span>Max. 600 characters</span></label>
                        <textarea id="about_lead_en" name="about_lead_en" rows="3" maxlength="600">{{ old('about_lead_en', $profile->about_lead_en) }}</textarea>
                        @error('about_lead_en')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="about_story">Cerita lengkap <span>Pisahkan paragraf dengan baris baru</span></label>
                        <textarea id="about_story" name="about_story" rows="8" maxlength="6000" placeholder="Tulis cerita perjalanan, pendekatan kerja, atau hal yang Anda sukai.">{{ old('about_story', $profile->about_story) }}</textarea>
                        @error('about_story')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="about_story_en">Cerita lengkap (English) <span>Separate paragraphs with a new line</span></label>
                        <textarea id="about_story_en" name="about_story_en" rows="8" maxlength="6000">{{ old('about_story_en', $profile->about_story_en) }}</textarea>
                        @error('about_story_en')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-form-grid">
                        <div class="admin-field">
                            <label for="focus_text">Fokus saat ini</label>
                            <textarea id="focus_text" name="focus_text" rows="3" maxlength="400" placeholder="Apa yang sedang Anda kerjakan atau pelajari?">{{ old('focus_text', $profile->focus_text) }}</textarea>
                            @error('focus_text')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="focus_text_en">Current focus (English)</label>
                            <textarea id="focus_text_en" name="focus_text_en" rows="3" maxlength="400">{{ old('focus_text_en', $profile->focus_text_en) }}</textarea>
                            @error('focus_text_en')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="signature">Tanda tangan</label>
                            <input type="text" id="signature" name="signature" value="{{ old('signature', $profile->signature) }}" placeholder="Nama atau sapaan penutup" maxlength="120">
                            @error('signature')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="signature_en">Signature (English)</label>
                            <input type="text" id="signature_en" name="signature_en" value="{{ old('signature_en', $profile->signature_en) }}" maxlength="120">
                            @error('signature_en')<small>{{ $message }}</small>@enderror
                        </div>
                    </div>
                </section>

                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Statistik</span>
                            <h3>Angka pengalaman</h3>
                        </div>
                        <span class="admin-card__hint">Tampil di bagian tentang</span>
                    </div>

                    <div class="admin-form-grid">
                        <div class="admin-field">
                            <label for="years_experience">Tahun pengalaman</label>
                            <input type="number" id="years_experience" name="years_experience" value="{{ old('years_experience', $profile->years_experience) }}" min="0" max="80">
                            @error('years_experience')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="projects_count">Jumlah proyek</label>
                            <input type="number" id="projects_count" name="projects_count" value="{{ old('projects_count', $profile->projects_count) }}" min="0" max="99999">
                            @error('projects_count')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="clients_count">Jumlah klien</label>
                            <input type="number" id="clients_count" name="clients_count" value="{{ old('clients_count', $profile->clients_count) }}" min="0" max="99999">
                            @error('clients_count')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="works_count">Jumlah tugas</label>
                            <input type="number" id="works_count" name="works_count" value="{{ old('works_count', $profile->works_count) }}" min="0" max="99999">
                            @error('works_count')<small>{{ $message }}</small>@enderror
                        </div>
                    </div>
                </section>

                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Tautan sosial</span>
                            <h3>Media sosial</h3>
                        </div>
                        <span class="admin-card__hint">Kosongkan yang tidak digunakan</span>
                    </div>

                    <div class="admin-form-grid">
                        @foreach ($networks as $network => $label)
                            <div class="admin-field">
                                <label for="social_{{ $network }}">{{ $label }}</label>
                                <input type="text" id="social_{{ $network }}" name="socials[{{ $network }}]" value="{{ old('socials.'.$network, $socialValues[$network] ?? '') }}" placeholder="https://{{ $network }}.com/username" maxlength="500">
                                @error('socials.'.$network)<small>{{ $message }}</small>@enderror
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="admin-editor-side">
                <div class="admin-card admin-sticky-card">
                    <div class="admin-account-head">
                        <span class="admin-account-avatar">
                            <img src="{{ $photo ?: asset('assets/images/portrait-placeholder.svg') }}" alt="" aria-hidden="true">
                        </span>
                        <strong>{{ $profile->name }}</strong>
                        <span>{{ $profile->role ?: 'Admin situs' }}</span>
                    </div>

                    <div class="admin-account-meta">
                        <div>
                            <span>Email admin</span>
                            <strong>{{ auth()->user()->email }}</strong>
                        </div>
                        <div>
                            <span>Terdaftar</span>
                            <strong>{{ auth()->user()->created_at?->translatedFormat('d M Y') ?: '—' }}</strong>
                        </div>
                    </div>

                    <div class="admin-field">
                        <label class="admin-upload-box admin-upload-box--thumb" for="photo">
                            <span class="admin-upload-box__icon">{!! \App\Support\Icons::svg('image') !!}</span>
                            <div>
                                <strong>Foto profil</strong>
                                <small>JPG, PNG, WebP, atau SVG — maks. 4 MB.</small>
                                <span class="admin-file-name" data-file-name></span>
                            </div>
                            <input type="file" id="photo" name="photo" accept="image/*" data-file-input>
                        </label>
                        @error('photo')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label class="admin-upload-box" for="cv">
                            <span class="admin-upload-box__icon">{!! \App\Support\Icons::svg('upload') !!}</span>
                            <div>
                                <strong>CV / Resume</strong>
                                <small>PDF, DOC, atau DOCX — maks. 20 MB.</small>
                                <span class="admin-file-name" data-file-name></span>
                            </div>
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" data-file-input>
                        </label>
                        @error('cv')<small>{{ $message }}</small>@enderror

                        @if ($profile->cvUrl())
                            <div class="admin-current-file" style="margin-top: 12px">
                                <span class="admin-current-file__icon">{!! \App\Support\Icons::svg('file') !!}</span>
                                <div>
                                    <strong>CV saat ini</strong>
                                    <a href="{{ $profile->cvUrl() }}" target="_blank" rel="noopener">Buka berkas</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="admin-divider"></div>

                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Keamanan</span>
                            <h3>Kata sandi</h3>
                        </div>
                    </div>

                    <div class="admin-field">
                        <label for="current_password">Kata sandi saat ini</label>
                        <input type="password" id="current_password" name="current_password" placeholder="••••••••" autocomplete="current-password">
                        @error('current_password')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="password">Kata sandi baru <span>Min. 8 karakter</span></label>
                        <input type="password" id="password" name="password" placeholder="Biarkan kosong jika tidak diubah" autocomplete="new-password">
                        @error('password')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="password_confirmation">Konfirmasi kata sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi baru" autocomplete="new-password">
                    </div>

                    <div class="admin-divider"></div>

                    <div class="admin-editor-actions">
                        <button class="admin-button admin-button--gold admin-button--full" type="submit">
                            {!! \App\Support\Icons::svg('check') !!}
                            Simpan perubahan
                        </button>
                        <a class="admin-button admin-button--ghost admin-button--full" href="{{ route('admin.dashboard') }}">Batal</a>
                    </div>

                    <p class="admin-muted" style="margin-top: 16px">
                        Perubahan profil langsung tampil di halaman publik setelah disimpan.
                    </p>
                </div>
            </aside>
        </div>
    </form>
@endsection
