<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeds the public content. Safe to run again: features and industries are
     * matched by slug, and FAQs are rebuilt. The admin user is created with
     * `php artisan make:filament-user`, never seeded.
     */
    public function run(): void
    {
        $this->call([
            FeatureSeeder::class,
            IndustrySeeder::class,
            FeatureContentSeeder::class,
            IndustryContentSeeder::class,
            PricingSeeder::class,
            FaqSeeder::class,
            PostSeeder::class,
        ]);
    }
}
