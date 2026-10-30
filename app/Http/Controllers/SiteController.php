<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Skill;
use App\Models\Task;
use App\Support\Portfolio;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $profile = Portfolio::profile();

        $skills = Skill::query()->ordered()->get();

        $tasks = Task::query()->published()->ordered()->get();

        $counts = [
            'all' => $tasks->count(),
            'video' => $tasks->where('type', 'video')->count(),
            'document' => $tasks->where('type', 'document')->count(),
            'text' => $tasks->where('type', 'text')->count(),
        ];

        return view('site.home', [
            'profile' => $profile,
            'socials' => Portfolio::socials(),
            'skills' => $skills,
            'tasks' => $tasks,
            'counts' => $counts,
            'marquee' => Portfolio::marquee(),
        ]);
    }

    public function contact(Request $request)
    {
        if ($request->filled('website')) {
            return redirect()->to(route('home').'#contact')->with('contact_success', trans('contact.sent'));
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:4000'],
        ], [
            'name.required' => trans('common.required'),
            'email.required' => trans('common.required'),
            'email.email' => trans('form.invalid_email'),
            'message.required' => trans('common.required'),
        ]);

        ContactMessage::create($data);

        return redirect()->to(route('home').'#contact')->with('contact_success', trans('contact.sent'));
    }
}
