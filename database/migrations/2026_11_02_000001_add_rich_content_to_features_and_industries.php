<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // The detail pages need more than a one-line summary: an overview, titled
    // highlights, and a walk through a working day. All translatable (JSON by locale).
    public function up(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->json('overview')->nullable()->after('summary');   // paragraphs separated by a blank line
            $table->json('highlights')->nullable()->after('overview'); // [{title, text}, ...]
            $table->json('steps')->nullable()->after('highlights');    // ["...", ...]
            $table->string('help_slug')->nullable()->after('icon');    // the help center category to link to
        });

        Schema::table('industries', function (Blueprint $table) {
            $table->json('overview')->nullable()->after('summary');
            $table->json('highlights')->nullable()->after('overview');
            $table->json('steps')->nullable()->after('highlights');
            $table->json('feature_slugs')->nullable()->after('icon'); // features this trade relies on (not translated)
        });
    }

    public function down(): void
    {
        Schema::table('features', fn (Blueprint $table) => $table->dropColumn(['overview', 'highlights', 'steps', 'help_slug']));
        Schema::table('industries', fn (Blueprint $table) => $table->dropColumn(['overview', 'highlights', 'steps', 'feature_slugs']));
    }
};
