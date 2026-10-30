<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Skill;
use App\Models\Task;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $tasks = Task::query()->ordered()->get();
        $skills = Skill::query()->ordered()->get();
        $messages = ContactMessage::query()->latest()->get();

        $typeCounts = [
            'video' => $tasks->where('type', 'video')->count(),
            'document' => $tasks->where('type', 'document')->count(),
            'text' => $tasks->where('type', 'text')->count(),
        ];

        $total = max(1, $tasks->count());

        return view('admin.dashboard', [
            'tasksCount' => $tasks->count(),
            'skillsCount' => $skills->count(),
            'messagesCount' => $messages->count(),
            'unreadCount' => $messages->whereNull('read_at')->count(),
            'featuredCount' => $tasks->where('featured', true)->count(),
            'avgSkill' => $skills->isEmpty() ? 0 : (int) round($skills->avg('level')),
            'typeCounts' => $typeCounts,
            'typePercent' => [
                'video' => (int) round($typeCounts['video'] / $total * 100),
                'document' => (int) round($typeCounts['document'] / $total * 100),
                'text' => (int) round($typeCounts['text'] / $total * 100),
            ],
            'recentTasks' => $tasks->take(5),
            'recentMessages' => $messages->take(4),
            'categories' => $skills->pluck('category')->filter()->unique()->count(),
        ]);
    }
}
