<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $filter = $request->query('type');
        $filter = in_array($filter, Task::TYPES, true) ? $filter : null;

        $tasks = Task::query()->ofType($filter)->ordered()->get();

        return view('admin.tasks.index', [
            'tasks' => $tasks,
            'filter' => $filter,
            'counts' => [
                'all' => Task::count(),
                'video' => Task::where('type', 'video')->count(),
                'document' => Task::where('type', 'document')->count(),
                'text' => Task::where('type', 'text')->count(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.tasks.form', [
            'task' => new Task(['type' => 'video', 'published_at' => now()]),
            'action' => route('admin.tasks.store'),
            'method' => 'POST',
            'heading' => 'Tambah Tugas',
            'subheading' => 'Susun tugas baru untuk ditampilkan pada portofolio publik.',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $task = new Task;
        $this->fill($task, $request, $data);
        $task->save();

        return redirect()->route('admin.tasks.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Task $task)
    {
        return view('admin.tasks.form', [
            'task' => $task,
            'action' => route('admin.tasks.update', $task),
            'method' => 'PUT',
            'heading' => 'Ubah Tugas',
            'subheading' => 'Perbarui detail, media, dan status publikasi tugas.',
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $data = $this->validated($request, $task);

        $this->fill($task, $request, $data);
        $task->save();

        return redirect()->route('admin.tasks.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        $this->deleteUpload($task->thumbnail);
        $this->deleteUpload($task->file_path);

        if ($task->video_kind === 'file') {
            $this->deleteUpload($task->video_url);
        }

        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    private function validated(Request $request, ?Task $task = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'title_en' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180'],
            'type' => ['required', Rule::in(Task::TYPES)],
            'category' => ['nullable', 'string', 'max:100'],
            'category_en' => ['nullable', 'required_with:category', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1200'],
            'description_en' => ['nullable', 'required_with:description', 'string', 'max:1200'],
            'content' => ['nullable', 'string', 'max:20000'],
            'content_en' => ['nullable', 'required_with:content', 'string', 'max:20000'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'video_kind' => ['nullable', Rule::in(['embed', 'link', 'file'])],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:102400'],
            'document_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,txt', 'max:20480'],
            'featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }

    private function fill(Task $task, Request $request, array $data): void
    {
        $task->title = $data['title'];
        $task->title_en = $data['title_en'] ?? null;
        $task->slug = $this->uniqueSlug($data['slug'] ?? $data['title'], $task);
        $task->type = $data['type'];
        $task->category = $data['category'] ?? null;
        $task->category_en = $data['category_en'] ?? null;
        $task->description = $data['description'] ?? null;
        $task->description_en = $data['description_en'] ?? null;
        $task->content = $data['content'] ?? null;
        $task->content_en = $data['content_en'] ?? null;
        $task->featured = $request->boolean('featured');
        $task->published_at = $data['published_at'] ?? now()->toDateString();
        $task->sort_order = $data['sort_order'] ?? ((int) Task::max('sort_order') + 1);

        if ($request->hasFile('thumbnail')) {
            $task->thumbnail = $this->storeUpload($request->file('thumbnail'), 'thumbnails', $task->thumbnail);
        } elseif ($request->boolean('remove_thumbnail')) {
            $this->deleteUpload($task->thumbnail);
            $task->thumbnail = null;
        }

        if ($task->type === 'video') {
            $kind = $data['video_kind'] ?? 'link';
            $task->video_kind = $kind;

            if ($kind === 'file') {
                if ($request->hasFile('video_file')) {
                    if ($task->video_kind === 'file') {
                        $this->deleteUpload($task->video_url);
                    }
                    $task->video_url = $this->storeUpload($request->file('video_file'), 'videos');
                } elseif ($request->boolean('remove_video')) {
                    $this->deleteUpload($task->video_url);
                    $task->video_url = null;
                }
            } else {
                $task->video_url = $data['video_url'] ?? null;
            }
        }

        if ($task->type === 'document') {
            if ($request->hasFile('document_file')) {
                $this->deleteUpload($task->file_path);
                $file = $request->file('document_file');
                $task->file_name = $file->getClientOriginalName();
                $task->file_size = $file->getSize();
                $task->file_extension = strtolower($file->getClientOriginalExtension());
                $task->file_path = $this->storeUpload($file, 'documents');
            } elseif ($request->boolean('remove_file')) {
                $this->deleteUpload($task->file_path);
                $task->file_path = null;
                $task->file_name = null;
                $task->file_size = null;
                $task->file_extension = null;
            }
        }
    }

    private function uniqueSlug(string $value, Task $task): string
    {
        $base = Str::slug($value) ?: 'tugas';
        $slug = $base;
        $index = 2;

        while (Task::where('slug', $slug)->whereKeyNot($task->getKey() ?? 0)->exists()) {
            $slug = $base.'-'.$index++;
        }

        return $slug;
    }
}
