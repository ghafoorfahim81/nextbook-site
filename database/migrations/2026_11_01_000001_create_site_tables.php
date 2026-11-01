<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Translatable columns are JSON objects keyed by locale: {"fa": "...", "en": "..."}.
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('icon')->default('Boxes');
            $table->string('status')->default('available'); // available | soon
            $table->json('title');
            $table->json('summary');
            $table->json('points')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('business_type')->nullable(); // key in the ERP's config/business_profiles.php
            $table->string('icon')->default('Store');
            $table->json('title');
            $table->json('summary');
            $table->json('points')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('price_label'); // free text: a figure, or "contact us". Never invented.
            $table->json('period')->nullable();
            $table->json('points')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->json('question');
            $table->json('answer');
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('help_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('icon')->default('BookOpen');
            $table->json('title');
            $table->json('summary')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('help_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('help_category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('summary')->nullable();
            $table->json('content')->nullable(); // HTML per locale
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('helpful_yes')->default(0);
            $table->unsignedInteger('helpful_no')->default(0);
            $table->timestamps();
        });

        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('business_type')->nullable();
            $table->unsignedSmallInteger('branches')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new'); // new | contacted | demo_done | customer | lost
            $table->string('locale', 5)->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['contact_messages', 'demo_requests', 'help_articles', 'help_categories', 'faqs', 'pricing_plans', 'industries', 'features'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
