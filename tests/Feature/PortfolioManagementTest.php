<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\Task;
use App\Models\User;
use App\Support\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_english_switch_localizes_public_profile_content(): void
    {
        $this->from('/')->get('/locale/en')->assertRedirect('/');

        $this->get('/')
            ->assertOk()
            ->assertSee('Crafting digital experiences that are calm, considered, and full of character.')
            ->assertSee('I believe great work comes from lasting curiosity and attention to detail.')
            ->assertDontSee('Merancang pengalaman digital yang tenang');
    }

    public function test_admin_can_create_update_and_delete_bilingual_task(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.tasks.store'), [
            'title' => 'Tugas awal',
            'title_en' => 'First task',
            'type' => 'text',
            'category' => 'Tulisan',
            'category_en' => 'Writing',
            'description' => 'Ringkasan tugas.',
            'description_en' => 'Task summary.',
            'content' => 'Isi tugas.',
            'content_en' => 'Task content.',
            'published_at' => '2026-10-30',
            'sort_order' => 1,
        ])->assertRedirect(route('admin.tasks.index'));

        $task = Task::query()->where('slug', 'tugas-awal')->firstOrFail();
        $this->assertSame('First task', $task->title_en);

        $this->put(route('admin.tasks.update', $task), [
            'title' => 'Tugas diperbarui',
            'title_en' => 'Updated task',
            'type' => 'text',
            'category' => 'Tulisan',
            'category_en' => 'Writing',
            'description' => 'Ringkasan baru.',
            'description_en' => 'New summary.',
            'content' => 'Isi baru.',
            'content_en' => 'New content.',
            'published_at' => '2026-10-30',
            'sort_order' => 1,
        ])->assertRedirect(route('admin.tasks.index'));

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title_en' => 'Updated task']);

        $this->delete(route('admin.tasks.destroy', $task))->assertRedirect(route('admin.tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_admin_can_create_update_and_delete_bilingual_skill(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.skills.store'), [
            'name' => 'Analisis Data',
            'name_en' => 'Data Analysis',
            'category' => 'Data',
            'category_en' => 'Data',
            'level' => 85,
            'sort_order' => 1,
        ])->assertRedirect(route('admin.skills.index'));

        $skill = Skill::query()->where('name', 'Analisis Data')->firstOrFail();
        $this->assertSame('Data Analysis', $skill->name_en);

        $this->put(route('admin.skills.update', $skill), [
            'name' => 'Desain Produk',
            'name_en' => 'Product Design',
            'category' => 'Desain',
            'category_en' => 'Design',
            'level' => 90,
            'sort_order' => 2,
        ])->assertRedirect(route('admin.skills.index'));

        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name_en' => 'Product Design']);

        $this->delete(route('admin.skills.destroy', $skill))->assertRedirect(route('admin.skills.index'));
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    public function test_admin_pages_render_and_profile_translations_save(): void
    {
        $this->actingAs(User::factory()->create());

        foreach ([
            route('admin.dashboard'),
            route('admin.tasks.index'),
            route('admin.tasks.create'),
            route('admin.skills.index'),
            route('admin.skills.create'),
            route('admin.messages.index'),
            route('admin.profile.edit'),
        ] as $url) {
            $this->get($url)->assertOk();
        }

        $profile = Portfolio::profile();
        $this->put(route('admin.profile.update'), [
            'name' => 'Yardan Danz',
            'role' => 'Desainer Web',
            'role_en' => 'Web Designer',
            'tagline' => 'Tagline Indonesia.',
            'tagline_en' => 'English tagline.',
            'location' => 'Jakarta, Indonesia',
            'location_en' => 'Jakarta, Indonesia',
            'email' => 'hello@example.com',
            'phone' => '+62 812 0000 0000',
            'availability' => 'Terbuka untuk proyek baru',
            'availability_en' => 'Available for new projects',
            'about_lead' => 'Paragraf pembuka.',
            'about_lead_en' => 'Opening paragraph.',
            'about_story' => 'Cerita lengkap.',
            'about_story_en' => 'Full story.',
            'focus_text' => 'Fokus saat ini.',
            'focus_text_en' => 'Current focus.',
            'signature' => 'Salam.',
            'signature_en' => 'Regards.',
            'years_experience' => 5,
            'projects_count' => 10,
            'clients_count' => 8,
            'works_count' => 12,
            'socials' => [],
        ])->assertRedirect(route('admin.profile.edit'));

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'tagline_en' => 'English tagline.',
            'about_story_en' => 'Full story.',
        ]);
    }

    public function test_public_contact_form_accepts_a_valid_message(): void
    {
        $this->post('/contact', [
            'name' => 'Test Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Portfolio inquiry',
            'message' => 'I would like to discuss a project.',
        ])->assertSessionHas('contact_success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'visitor@example.com',
            'subject' => 'Portfolio inquiry',
        ]);
    }
}
