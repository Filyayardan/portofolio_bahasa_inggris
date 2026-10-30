@extends('layouts.admin')

@section('title', 'Tugas')
@section('kicker', 'Manajemen Konten')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-kicker">Koleksi portofolio</span>
            <h2>Tugas</h2>
            <p>Kelola video, dokumen, dan tulisan yang tampil pada bagian tugas di situs publik.</p>
        </div>
        <div class="admin-page-actions">
            <a class="admin-button admin-button--gold" href="{{ route('admin.tasks.create') }}">
                {!! \App\Support\Icons::svg('plus') !!}
                Tambah tugas
            </a>
        </div>
    </div>

    <div class="admin-toolbar">
        <div class="admin-filter-tabs">
            <a href="{{ route('admin.tasks.index') }}" class="{{ $filter === null ? 'is-active' : '' }}">
                Semua <span>{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('admin.tasks.index', ['type' => 'video']) }}" class="{{ $filter === 'video' ? 'is-active' : '' }}">
                Video <span>{{ $counts['video'] }}</span>
            </a>
            <a href="{{ route('admin.tasks.index', ['type' => 'document']) }}" class="{{ $filter === 'document' ? 'is-active' : '' }}">
                Dokumen <span>{{ $counts['document'] }}</span>
            </a>
            <a href="{{ route('admin.tasks.index', ['type' => 'text']) }}" class="{{ $filter === 'text' ? 'is-active' : '' }}">
                Tulisan <span>{{ $counts['text'] }}</span>
            </a>
        </div>

        <span class="admin-muted">{{ $tasks->count() }} tugas ditampilkan</span>
    </div>

    <div class="admin-card" style="padding: 4px 26px">
        @if ($tasks->isEmpty())
            <div class="admin-empty">
                {!! \App\Support\Icons::svg('layers') !!}
                <h4>Belum ada tugas</h4>
                <p>Tambahkan tugas pertama Anda — bisa berupa video, unggahan dokumen, atau tulisan.</p>
                <a class="admin-button admin-button--gold admin-button--small" href="{{ route('admin.tasks.create') }}">Tambah tugas</a>
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tugas</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Urutan</th>
                            <th>Tanggal</th>
                            <th class="admin-table__actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
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
                                <td>{{ $task->sort_order }}</td>
                                <td>{{ $task->date_label ?: '—' }}</td>
                                <td class="admin-table__actions">
                                    <div class="admin-row-actions">
                                        <a class="admin-icon-button" href="{{ route('admin.tasks.edit', $task) }}" aria-label="Ubah {{ $task->title }}">
                                            {!! \App\Support\Icons::svg('edit') !!}
                                        </a>
                                        <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" data-confirm="Hapus tugas “{{ $task->title }}”? Tindakan ini tidak dapat dibatalkan.">
                                            @csrf
                                            @method('DELETE')
                                            <button class="admin-icon-button admin-icon-button--danger" type="submit" aria-label="Hapus {{ $task->title }}">
                                                {!! \App\Support\Icons::svg('trash') !!}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
