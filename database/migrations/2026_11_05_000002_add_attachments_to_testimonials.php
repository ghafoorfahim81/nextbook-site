<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Photos, videos and voice notes, as paths on the public disk.
            $table->json('attachments')->nullable();
            // Filled when a visitor sends the testimonial from the site. Never shown publicly.
            $table->string('contact')->nullable();
            $table->string('source')->default('admin'); // admin | website
            $table->string('ip', 45)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['attachments', 'contact', 'source', 'ip']);
        });
    }
};
