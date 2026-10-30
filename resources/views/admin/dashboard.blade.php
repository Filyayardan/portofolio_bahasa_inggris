@extends('layouts.admin')

@section('title', 'Dashboard')
@section('kicker', 'Ringkasan Studio')

@php
    $firstName = explode(' ', trim(\App\Support\Portfolio::profile()->name))[0];
    $donutStyle = sprintf(
        'background: conic-gradient(var(--gold) 0 %1$d%%, #566a7a %1$d%% %2$d%%, var(--success) %2$d%% 100%%)',
        $typePercent['video'],
        $typePercent['video'] + $typePercent['document']
    );
@endphp

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-kicker">Selamat datang kembali</span>
            <h2>Halo, {{ $firstName }}.</h2>
            <p>Berikut ringkasan portofolio dan aktivitas terbaru. Semua yang tampil di situs dapat dikelola dari sini.</p>
        </div>
        <div class="admin-page-actions">
            <a class="admin-button admin-button--gold" href="{{ route('admin.tasks.create') }}">
                {!! \App\Support\Icons::svg('plus') !!}
                Karya baru
            </a>
        </div>
    </div>

    <div class="admin-stats">
        <a class="admin-stat" href="{{ route('admin.tasks.index') }}">
            <div class="admin-stat__top">
                <span class="admin-stat__icon">{!! \App\Support\Icons::svg('layers') !!}</span>
                <span class="admin-stat__trend">{{ $featuredCount }} pilihan</span>
            </div>
            <strong>{{ $tasksCount }}</strong>
            <span class="admin-stat__label">Total karya</span>
            <div class="admin-stat__line"><span style="width: 100%"></span></div>
        </a>

        <a class="admin-stat" href="{{ route('admin.skills.index') }}">
            <div class="admin-stat__top">
                <span class="admin-stat__icon">{!! \App\Support\Icons::svg('spark') !!}</span>
                <span class="admin-stat__trend">{{ $avgSkill }}% rata-rata</span>
            </div>
            <strong>{{ $skillsCount }}</strong>
            <span class="admin-stat__label">Keahlian</span>
            <div class="admin-stat__line"><span style="width: {{ min(100, $skillsCount * 12) }}%"></span></div>
        </a>

        <a class="admin-stat" href="{{ route('admin.messages.index') }}">
            <div class="admin-stat__top">
                <span class="admin-stat__icon">{!! \App\Support\Icons::svg('mail') !!}</span>
                <span class="admin-stat__trend">{{ $unreadCount }} belum dibaca</span>
            </div>
            <strong>{{ $messagesCount }}</strong>
            <span class="admin-stat__label">Pesan masuk</span>
            <div class="admin-stat__line"><span style="width: {{ $messagesCount > 0 ? min(100, $unreadCount / $messagesCount * 100) : 0 }}%"></span></div>
        </a>

        <a class="admin-stat" href="{{ route('admin.profile.edit') }}">
            <div class="admin-stat__top">
                <span class="admin-stat__icon">{!! \App\Support\Icons::svg('chart') !!}</span>
                <span class="admin-stat__trend">{{ $categories }} kategori</span>
            </div>
            <strong>{{ $avgSkill }}<span style="font-size: 18px">%</span></strong>
            <span class="admin-stat__label">Rata-rata keahlian</span>
            <div class="admin-stat__line"><span style="width: {{ $avgSkill }}%"></span></div>
        </a>
    </div>

    <div class="admin-dashboard-grid">
        <section class="admin-card">
            <div class="admin-card__head">
                <div>
                    <span class="admin-card__kicker">Karya terbaru</span>
                    <h3>Koleksi terkini</h3>
                </div>
                <a class="admin-link" href="{{ route('admin.tasks.index') }}">
                    Lihat semua
                    {!! \App\Support\Icons::svg('arrow-right') !!}
                </a>
            </div>

            @if ($recentTasks->isEmpty())
                <div class="admin-empty">
                    {!! \App\Support\Icons::svg('layers') !!}
                    <h4>Belum ada karya</h4>
                    <p>Mulai dengan menambahkan karya pertama Anda ke portofolio.</p>
                    <a class="admin-button admin-button--gold admin-button--small" href="{{ route('admin.tasks.create') }}">Tambah karya</a>
                </div>
            @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Karya</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="admin-table__actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTasks as $task)
                                <tr>
                                    <td>
                                        <div class="admin-task-title">
                                            <span class="admin-task-thumb" @if ($task->thumbnail) style="background-image: url('{{ $task->thumbnail_url }}')" @endif>
                                                @unless ($task->thumbnail)
                                                    {!! \App\Support\Icons::svg('image') !!}
                                                @endunless
                                            </span>
                                            <div>
                                                <strong>{{ $task->title }}</strong>
                                                <small>{{ $task->category ?: 'Tanpa kategori' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="admin-pill admin-pill--{{ $task->type }}">
                                            {!! \App\Support\Icons::svg($task->type === 'video' ? 'film' : ($task->type === 'document' ? 'file' : 'pen')) !!}
                                            {{ $task->type_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="admin-status {{ $task->featured ? 'is-featured' : '' }}">
                                            <i></i>{{ $task->featured ? 'Pilihan' : 'Aktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $task->date_label ?: '—' }}</td>
                                    <td class="admin-table__actions">
                                        <a class="admin-icon-button" href="{{ route('admin.tasks.edit', $task) }}" aria-label="Ubah {{ $task->title }}">
                                            {!! \App\Support\Icons::svg('edit') !!}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="admin-dashboard-side">
            <section class="admin-card">
                <div class="admin-card__head">
                    <div>
                        <span class="admin-card__kicker">Komposisi</span>
                        <h3>Jenis karya</h3>
                    </div>
                    <span class="admin-card__hint">{{ $tasksCount }} total</span>
                </div>

                <div class="admin-donut-wrap">
                    <div class="admin-donut" style="{{ $donutStyle }}">
                        <div>
                            <strong>{{ $tasksCount }}</strong>
                            <span>Karya</span>
                        </div>
                    </div>

                    <div class="admin-legend">
                        <span><i style="background: var(--gold)"></i> Video <b>{{ $typeCounts['video'] }}</b></span>
                        <span><i style="background: #566a7a"></i> Dokumen <b>{{ $typeCounts['document'] }}</b></span>
                        <span><i style="background: var(--success)"></i> Teks <b>{{ $typeCounts['text'] }}</b></span>
                    </div>
                </div>
            </section>

            <section class="admin-card">
                <div class="admin-card__head">
                    <div>
                        <span class="admin-card__kicker">Kotak masuk</span>
                        <h3>Pesan terbaru</h3>
                    </div>
                    <a class="admin-link" href="{{ route('admin.messages.index') }}">Buka</a>
                </div>

                @if ($recentMessages->isEmpty())
                    <p class="admin-muted">Belum ada pesan masuk.</p>
                @else
                    <div class="admin-mini-messages">
                        @foreach ($recentMessages as $message)
                            <a class="admin-mini-message {{ $message->is_unread ? 'is-unread' : '' }}" href="{{ route('admin.messages.show', $message) }}">
                                <span class="admin-avatar admin-avatar--small">{{ $message->initials }}</span>
                                <div>
                                    <strong>{{ $message->name }}</strong>
                                    <span>{{ $message->subject_or_fallback }}</span>
                                </div>
                                @if ($message->is_unread)
                                    <i aria-hidden="true"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="admin-card admin-card--progress">
                <div class="admin-card__head">
                    <div>
                        <span class="admin-card__kicker">Keahlian</span>
                        <h3>Rata-rata penguasaan</h3>
                    </div>
                    <span class="admin-progress-value">{{ $avgSkill }}%</span>
                </div>
                <p class="admin-muted">Diambil dari seluruh keahlian yang tampil di halaman publik.</p>
                <div class="admin-progress"><span style="width: {{ $avgSkill }}%"></span></div>
                <a class="admin-link" href="{{ route('admin.skills.index') }}">
                    Kelola keahlian
                    {!! \App\Support\Icons::svg('arrow-right') !!}
                </a>
            </section>
        </div>
    </div>

    <div class="admin-quick-actions">
        <a class="admin-quick-action" href="{{ route('admin.tasks.create') }}">
            {!! \App\Support\Icons::svg('plus') !!}
            <span>
                <strong>Tambah karya</strong>
                <small>Video, dokumen, atau tulisan</small>
            </span>
            {!! \App\Support\Icons::svg('arrow-right') !!}
        </a>
        <a class="admin-quick-action" href="{{ route('admin.skills.create') }}">
            {!! \App\Support\Icons::svg('spark') !!}
            <span>
                <strong>Tambah keahlian</strong>
                <small>Perbarui daftar kemampuan</small>
            </span>
            {!! \App\Support\Icons::svg('arrow-right') !!}
        </a>
        <a class="admin-quick-action" href="{{ route('admin.profile.edit') }}">
            {!! \App\Support\Icons::svg('user') !!}
            <span>
                <strong>Perbarui profil</strong>
                <small>Foto, bio, dan tautan sosial</small>
            </span>
            {!! \App\Support\Icons::svg('arrow-right') !!}
        </a>
    </div>
@endsection
