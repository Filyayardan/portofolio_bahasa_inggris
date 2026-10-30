@extends('layouts.admin')

@section('title', $heading)
@section('kicker', 'Editor Karya')

@php
    $types = [
        ['value' => 'video', 'label' => 'Video', 'description' => 'Tautan YouTube, Vimeo, atau berkas video.', 'icon' => 'film'],
        ['value' => 'document', 'label' => 'Dokumen', 'description' => 'PDF, Word, atau presentasi untuk diunduh.', 'icon' => 'file'],
        ['value' => 'text', 'label' => 'Tulisan', 'description' => 'Catatan, studi kasus, atau artikel singkat.', 'icon' => 'pen'],
    ];
    $selectedType = old('type', $task->type ?: 'video');
    $videoKind = old('video_kind', $task->video_kind ?: 'link');
@endphp

@section('content')
    <div class="admin-page-head">
        <div>
            <a class="admin-back-link" href="{{ route('admin.tasks.index') }}">
                {!! \App\Support\Icons::svg('arrow-left') !!}
                Kembali ke daftar karya
            </a>
            <h2>{{ $heading }}</h2>
            <p>{{ $subheading }}</p>
        </div>
        <div class="admin-page-actions">
            <a class="admin-button admin-button--ghost" href="{{ route('admin.tasks.index') }}">Batal</a>
            <button class="admin-button admin-button--gold" type="submit" form="taskForm">
                {!! \App\Support\Icons::svg('check') !!}
                Simpan karya
            </button>
        </div>
    </div>

    <form class="admin-form" id="taskForm" method="POST" action="{{ $action }}" enctype="multipart/form-data" data-task-form>
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="admin-editor-grid">
            <div class="admin-editor-main">
                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Detail utama</span>
                            <h3>Informasi karya</h3>
                        </div>
                    </div>

                    <div class="admin-field">
                        <label for="title">Judul karya</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" placeholder="Contoh: Analisis tren penjualan kuartal IV" required maxlength="160">
                        @error('title')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-form-grid">
                        <div class="admin-field">
                            <label for="slug">Slug <span>Opsional</span></label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $task->slug) }}" placeholder="otomatis-dari-judul" maxlength="180">
                            @error('slug')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="admin-field">
                            <label for="category">Kategori</label>
                            <input type="text" id="category" name="category" value="{{ old('category', $task->category) }}" placeholder="Data, Desain, Riset…" maxlength="100">
                            @error('category')<small>{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="admin-field" style="margin-top: 18px">
                        <label for="description">Ringkasan <span>Maks. 1200 karakter</span></label>
                        <textarea id="description" name="description" rows="3" maxlength="1200" placeholder="Deskripsi singkat yang tampil pada kartu karya.">{{ old('description', $task->description) }}</textarea>
                        @error('description')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="content">Konten tambahan <span>Opsional</span></label>
                        <textarea id="content" name="content" rows="7" maxlength="20000" placeholder="Tulis studi kasus, catatan proses, atau detail lain yang muncul di modal karya.">{{ old('content', $task->content) }}</textarea>
                        @error('content')<small>{{ $message }}</small>@enderror
                    </div>
                </section>

                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Tipe konten</span>
                            <h3>Jenis karya</h3>
                        </div>
                        <span class="admin-card__hint">Pilih satu</span>
                    </div>

                    <div class="admin-type-grid">
                        @foreach ($types as $type)
                            <label class="admin-type-option">
                                <input type="radio" name="type" value="{{ $type['value'] }}" @checked($selectedType === $type['value'])>
                                <span class="admin-type-option__icon">{!! \App\Support\Icons::svg($type['icon']) !!}</span>
                                <span class="admin-type-option__body">
                                    <strong>{{ $type['label'] }}</strong>
                                    <small>{{ $type['description'] }}</small>
                                </span>
                                <span class="admin-type-option__check">{!! \App\Support\Icons::svg('check') !!}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('type')<small style="display:block;margin-top:8px;color:var(--danger)">{{ $message }}</small>@enderror

                    <div class="admin-type-panel" data-type-panel="video">
                        <div class="admin-field">
                            <label for="video_kind">Sumber video</label>
                            <select id="video_kind" name="video_kind">
                                <option value="link" @selected($videoKind === 'link')>Tautan embed / URL</option>
                                <option value="embed" @selected($videoKind === 'embed')>Kode embed</option>
                                <option value="file" @selected($videoKind === 'file')>Unggah berkas video</option>
                            </select>
                        </div>

                        <div class="admin-field">
                            <label for="video_url">Tautan video <span>YouTube, Vimeo, atau URL</span></label>
                            <input type="text" id="video_url" name="video_url" value="{{ old('video_url', $task->video_kind !== 'file' ? $task->video_url : '') }}" placeholder="https://www.youtube.com/watch?v=…" maxlength="1000">
                        </div>

                        @if ($task->video_kind === 'file' && $task->video_url)
                            <div class="admin-current-file">
                                <span class="admin-current-file__icon">{!! \App\Support\Icons::svg('video') !!}</span>
                                <div>
                                    <strong>Video saat ini</strong>
                                    <a href="{{ $task->video_url }}" target="_blank" rel="noopener">Lihat berkas</a>
                                </div>
                            </div>
                        @endif

                        <div class="admin-field">
                            <label class="admin-upload-box" for="video_file">
                                <span class="admin-upload-box__icon">{!! \App\Support\Icons::svg('upload') !!}</span>
                                <div>
                                    <strong>Unggah video</strong>
                                    <small>MP4, WebM, atau MOV — maks. 100 MB.</small>
                                    <span class="admin-file-name" data-file-name></span>
                                </div>
                                <input type="file" id="video_file" name="video_file" accept="video/mp4,video/webm,video/quicktime" data-file-input>
                            </label>
                            @error('video_file')<small>{{ $message }}</small>@enderror
                        </div>

                        @if ($task->video_kind === 'file' && $task->video_url)
                            <label class="admin-checkbox admin-checkbox--danger">
                                <input type="checkbox" name="remove_video" value="1">
                                Hapus video yang tersimpan
                            </label>
                        @endif
                    </div>

                    <div class="admin-type-panel" data-type-panel="document">
                        @if ($task->file_path)
                            <div class="admin-current-file">
                                <span class="admin-current-file__icon">{!! \App\Support\Icons::svg('file') !!}</span>
                                <div>
                                    <strong>{{ $task->file_name ?: 'Dokumen saat ini' }}</strong>
                                    <a href="{{ $task->file_url }}" target="_blank" rel="noopener">Buka dokumen</a>
                                </div>
                            </div>
                        @endif

                        <div class="admin-field">
                            <label class="admin-upload-box" for="document_file">
                                <span class="admin-upload-box__icon">{!! \App\Support\Icons::svg('upload') !!}</span>
                                <div>
                                    <strong>Unggah dokumen</strong>
                                    <small>PDF, DOC, DOCX, PPT, PPTX, atau TXT — maks. 20 MB.</small>
                                    <span class="admin-file-name" data-file-name></span>
                                </div>
                                <input type="file" id="document_file" name="document_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt" data-file-input>
                            </label>
                            @error('document_file')<small>{{ $message }}</small>@enderror
                        </div>

                        @if ($task->file_path)
                            <label class="admin-checkbox admin-checkbox--danger">
                                <input type="checkbox" name="remove_file" value="1">
                                Hapus dokumen yang tersimpan
                            </label>
                        @endif
                    </div>
                </section>

                <section class="admin-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Visual</span>
                            <h3>Thumbnail karya</h3>
                        </div>
                        <span class="admin-card__hint">Rasio 16:10</span>
                    </div>

                    <div class="admin-upload-grid">
                        <div class="admin-field">
                            <label class="admin-upload-box" for="thumbnail">
                                <span class="admin-upload-box__icon">{!! \App\Support\Icons::svg('image') !!}</span>
                                <div>
                                    <strong>Pilih gambar sampul</strong>
                                    <small>JPG, PNG, WebP, atau SVG — maks. 4 MB.</small>
                                    <span class="admin-file-name" data-file-name></span>
                                </div>
                                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" data-file-input>
                            </label>
                            @error('thumbnail')<small>{{ $message }}</small>@enderror

                            @if ($task->thumbnail)
                                <label class="admin-checkbox admin-checkbox--danger" style="margin-top: 12px">
                                    <input type="checkbox" name="remove_thumbnail" value="1">
                                    Hapus thumbnail saat ini
                                </label>
                            @endif
                        </div>

                        <div class="admin-thumb-preview">
                            @if ($task->thumbnail)
                                <img src="{{ $task->thumbnail_url }}" alt="Thumbnail {{ $task->title }}">
                            @else
                                <img src="{{ asset('assets/images/portrait-placeholder.svg') }}" alt="Belum ada thumbnail">
                            @endif
                            <small class="admin-muted">Pratinjau thumbnail</small>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="admin-editor-side">
                <div class="admin-card admin-sticky-card">
                    <div class="admin-card__head">
                        <div>
                            <span class="admin-card__kicker">Publikasi</span>
                            <h3>Pengaturan</h3>
                        </div>
                    </div>

                    <label class="admin-switch">
                        <input type="checkbox" name="featured" value="1" @checked(old('featured', $task->featured))>
                        <span class="admin-switch__track"><i></i></span>
                        <span>
                            <strong>Karya pilihan</strong>
                            <small>Tampilkan label unggulan pada kartu karya.</small>
                        </span>
                    </label>

                    <div class="admin-field">
                        <label for="published_at">Tanggal publikasi</label>
                        <input type="date" id="published_at" name="published_at" value="{{ old('published_at', $task->published_at?->format('Y-m-d')) }}">
                        @error('published_at')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-field">
                        <label for="sort_order">Urutan tampil <span>Angka kecil lebih dulu</span></label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $task->sort_order) }}" min="0" max="9999">
                        @error('sort_order')<small>{{ $message }}</small>@enderror
                    </div>

                    <div class="admin-divider"></div>

                    <div class="admin-editor-actions">
                        <button class="admin-button admin-button--gold admin-button--full" type="submit">
                            {!! \App\Support\Icons::svg('check') !!}
                            {{ $method === 'POST' ? 'Simpan karya' : 'Perbarui karya' }}
                        </button>
                        <a class="admin-button admin-button--ghost admin-button--full" href="{{ route('admin.tasks.index') }}">Batal</a>
                    </div>

                    <p class="admin-muted" style="margin-top: 16px">
                        Perubahan langsung tampil di halaman publik setelah disimpan.
                    </p>
                </div>
            </aside>
        </div>
    </form>
@endsection
