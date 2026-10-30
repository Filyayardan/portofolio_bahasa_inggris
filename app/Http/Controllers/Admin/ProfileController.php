<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Support\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use HandlesUploads;

    public function edit()
    {
        return view('admin.profile.edit', [
            'profile' => Portfolio::profile(),
            'networks' => Portfolio::allSocialNetworks(),
        ]);
    }

    public function update(Request $request)
    {
        $profile = Portfolio::profile();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:160'],
            'role_en' => ['nullable', 'required_with:role', 'string', 'max:160'],
            'tagline' => ['nullable', 'string', 'max:300'],
            'tagline_en' => ['nullable', 'required_with:tagline', 'string', 'max:300'],
            'location' => ['nullable', 'string', 'max:160'],
            'location_en' => ['nullable', 'required_with:location', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:60'],
            'availability' => ['nullable', 'string', 'max:120'],
            'availability_en' => ['nullable', 'required_with:availability', 'string', 'max:120'],
            'about_lead' => ['nullable', 'string', 'max:600'],
            'about_lead_en' => ['nullable', 'required_with:about_lead', 'string', 'max:600'],
            'about_story' => ['nullable', 'string', 'max:6000'],
            'about_story_en' => ['nullable', 'required_with:about_story', 'string', 'max:6000'],
            'focus_text' => ['nullable', 'string', 'max:400'],
            'focus_text_en' => ['nullable', 'required_with:focus_text', 'string', 'max:400'],
            'signature' => ['nullable', 'string', 'max:120'],
            'signature_en' => ['nullable', 'required_with:signature', 'string', 'max:120'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:80'],
            'projects_count' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'clients_count' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'works_count' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'socials' => ['nullable', 'array'],
            'socials.*' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $profile->fill([
            'name' => $data['name'],
            'role' => $data['role'] ?? null,
            'role_en' => $data['role_en'] ?? null,
            'tagline' => $data['tagline'] ?? null,
            'tagline_en' => $data['tagline_en'] ?? null,
            'location' => $data['location'] ?? null,
            'location_en' => $data['location_en'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'availability' => $data['availability'] ?? null,
            'availability_en' => $data['availability_en'] ?? null,
            'about_lead' => $data['about_lead'] ?? null,
            'about_lead_en' => $data['about_lead_en'] ?? null,
            'about_story' => $data['about_story'] ?? null,
            'about_story_en' => $data['about_story_en'] ?? null,
            'focus_text' => $data['focus_text'] ?? null,
            'focus_text_en' => $data['focus_text_en'] ?? null,
            'signature' => $data['signature'] ?? null,
            'signature_en' => $data['signature_en'] ?? null,
            'years_experience' => $data['years_experience'] ?? 0,
            'projects_count' => $data['projects_count'] ?? 0,
            'clients_count' => $data['clients_count'] ?? 0,
            'works_count' => $data['works_count'] ?? 0,
            'socials' => collect($data['socials'] ?? [])
                ->filter(fn ($url) => is_string($url) && trim($url) !== '')
                ->map(fn ($url, $network) => [
                    'network' => $network,
                    'label' => Portfolio::allSocialNetworks()[$network] ?? ucfirst($network),
                    'url' => trim($url),
                ])
                ->values()
                ->all(),
        ]);

        if ($request->hasFile('photo')) {
            $profile->photo = $this->storeUpload($request->file('photo'), 'profile', $profile->photo);
        }

        if ($request->hasFile('cv')) {
            $profile->cv_path = $this->storeUpload($request->file('cv'), 'documents', $profile->cv_path);
        }

        if ($request->filled('password')) {
            if (! $request->filled('current_password') || ! Hash::check($request->input('current_password'), $request->user()->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.'])->withInput();
            }

            $request->user()->forceFill([
                'password' => Hash::make($request->input('password')),
            ])->save();
        }

        $profile->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
