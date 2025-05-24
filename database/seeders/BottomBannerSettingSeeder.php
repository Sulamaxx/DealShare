<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BottomBannerSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'bottom_banner'], // Condition to find the record
            ['value' => 'images/banner.png'] // Values to create or update
        );

        $this->command->info('Bottom banner setting seeded successfully!');
    }
}
