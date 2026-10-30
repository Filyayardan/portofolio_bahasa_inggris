<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Yardan Danz');
            $table->string('role')->nullable();
            $table->text('tagline')->nullable();
            $table->string('location')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('availability')->nullable();
            $table->text('about_lead')->nullable();
            $table->text('about_story')->nullable();
            $table->text('focus_text')->nullable();
            $table->string('photo')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('signature')->nullable();
            $table->unsignedTinyInteger('years_experience')->default(5);
            $table->unsignedInteger('projects_count')->default(40);
            $table->unsignedInteger('clients_count')->default(25);
            $table->unsignedInteger('works_count')->default(60);
            $table->json('socials')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
