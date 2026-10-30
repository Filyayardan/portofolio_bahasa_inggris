<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::query()->ordered()->get();

        return view('admin.skills.index', [
            'skills' => $skills,
            'avgLevel' => $skills->isEmpty() ? 0 : (int) round($skills->avg('level')),
            'categories' => $skills->pluck('category')->filter()->unique()->count(),
            'topSkill' => $skills->sortByDesc('level')->first(),
        ]);
    }

    public function create()
    {
        return view('admin.skills.form', [
            'skill' => new Skill(['level' => 80, 'sort_order' => (int) Skill::max('sort_order') + 1]),
            'action' => route('admin.skills.store'),
            'method' => 'POST',
            'heading' => 'Tambah Keahlian',
            'subheading' => 'Tambahkan kemampuan baru beserta tingkat penguasaannya.',
        ]);
    }

    public function store(Request $request)
    {
        Skill::create($this->validated($request));

        return redirect()->route('admin.skills.index')->with('success', 'Keahlian berhasil ditambahkan.');
    }

    public function edit(Skill $skill)
    {
        return view('admin.skills.form', [
            'skill' => $skill,
            'action' => route('admin.skills.update', $skill),
            'method' => 'PUT',
            'heading' => 'Ubah Keahlian',
            'subheading' => 'Perbarui nama, kategori, dan tingkat penguasaan keahlian.',
        ]);
    }

    public function update(Request $request, Skill $skill)
    {
        $skill->update($this->validated($request));

        return redirect()->route('admin.skills.index')->with('success', 'Keahlian berhasil diperbarui.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Keahlian berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:100'],
            'level' => ['required', 'integer', 'min:0', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['level'] = max(0, min(100, (int) $data['level']));
        $data['sort_order'] = $data['sort_order'] ?? ((int) Skill::max('sort_order') + 1);

        return $data;
    }
}
