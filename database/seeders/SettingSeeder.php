<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'popular_deal_upvote_weight', 'value' => '1'],
            ['key' => 'popular_deal_downvote_weight', 'value' => '-0.5'],
            ['key' => 'popular_deal_comment_weight', 'value' => '0.8'],

        ];

        foreach ($settings as $settingData) {
            Setting::updateOrCreate(
                ['key' => $settingData['key']],
                ['value' => $settingData['value']]
            );
        }
    }
}
