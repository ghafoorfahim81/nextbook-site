<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            // Prices in US dollars. Null means "no fixed price": the plan then shows price_label ("contact us").
            $table->unsignedInteger('monthly')->nullable()->after('slug');
            $table->unsignedInteger('yearly')->nullable()->after('monthly');
            $table->json('audience')->nullable()->after('name');  // who the plan is for
            $table->json('includes')->nullable()->after('description'); // "Everything in Starter, plus:"
            $table->json('price_label')->nullable()->change();
        });

        Schema::create('pricing_addons', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('icon')->default('sparkles');
            $table->json('name');
            $table->json('description');
            $table->json('price_label')->nullable(); // e.g. "Contact us"
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_addons');

        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn(['monthly', 'yearly', 'audience', 'includes']);
        });
    }
};
