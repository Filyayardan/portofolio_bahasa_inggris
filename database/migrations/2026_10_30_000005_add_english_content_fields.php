<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('role_en')->nullable();
            $table->text('tagline_en')->nullable();
            $table->string('location_en')->nullable();
            $table->string('availability_en')->nullable();
            $table->text('about_lead_en')->nullable();
            $table->text('about_story_en')->nullable();
            $table->text('focus_text_en')->nullable();
            $table->string('signature_en')->nullable();
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->string('name_en')->nullable();
            $table->string('category_en')->nullable();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title_en')->nullable();
            $table->string('category_en')->nullable();
            $table->text('description_en')->nullable();
            $table->longText('content_en')->nullable();
        });

        DB::table('profiles')->where('role', 'Web Developer & Visual Designer')
            ->update(['role_en' => 'Web Developer & Visual Designer']);
        DB::table('profiles')->where('location', 'Jakarta, Indonesia')
            ->update(['location_en' => 'Jakarta, Indonesia']);
        DB::table('profiles')->where('signature', 'Yardan Danz')
            ->update(['signature_en' => 'Yardan Danz']);
        DB::table('profiles')->where('tagline', 'Merancang pengalaman digital yang tenang, terukur, dan berkarakter.')
            ->update(['tagline_en' => 'Crafting digital experiences that are calm, considered, and full of character.']);
        DB::table('profiles')->where('availability', 'Terbuka untuk proyek baru')
            ->update(['availability_en' => 'Available for new projects']);
        DB::table('profiles')->where('about_lead', 'Saya percaya karya yang baik lahir dari rasa ingin tahu yang panjang dan perhatian pada detail.')
            ->update(['about_lead_en' => 'I believe great work comes from lasting curiosity and attention to detail.']);
        DB::table('profiles')->where('about_story', "Saya menghabiskan beberapa tahun terakhir membantu brand, institusi, dan individu menerjemahkan ide mereka menjadi pengalaman digital yang terasa utuh.\nDi luar layar, saya suka mengamati tipografi, arsitektur, dan cara orang berinteraksi dengan benda sehari-hari.")
            ->update(['about_story_en' => "Over the past few years, I have helped brands, institutions, and individuals turn their ideas into cohesive digital experiences.\nAway from the screen, I enjoy observing typography, architecture, and the way people interact with everyday objects."]);
        DB::table('profiles')->where('focus_text', 'Membangun pengalaman digital yang bermakna, dari konsep hingga baris kode terakhir.')
            ->update(['focus_text_en' => 'Building meaningful digital experiences, from concept to the final line of code.']);
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'category_en', 'description_en', 'content_en']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'category_en']);
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'role_en', 'tagline_en', 'location_en', 'availability_en',
                'about_lead_en', 'about_story_en', 'focus_text_en', 'signature_en',
            ]);
        });
    }
};
