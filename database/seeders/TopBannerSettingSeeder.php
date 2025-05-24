<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopBannerSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'top_banner'], // Condition to find the record
            ['value' => 'images/banner.png'] // Values to create or update
        );

        $this->command->info('Top banner setting seeded successfully!');
    }
}
