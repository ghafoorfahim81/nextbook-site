<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Chapters of different guides share names ("glossary", "overview"), so an
    // article slug only has to be unique inside its own category.
    public function up(): void
    {
        Schema::table('help_articles', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['help_category_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('help_articles', function (Blueprint $table) {
            $table->dropUnique(['help_category_id', 'slug']);
            $table->unique('slug');
        });
    }
};
