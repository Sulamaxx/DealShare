<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HighlyVotedDealSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Setting::where('key', 'highly_voted_deal_upvote_count')->exists()) {
            Setting::create([
                'key' => 'highly_voted_deal_upvote_count',
                'value' => '50',
            ]);
            $this->command->info('Highly voted deal setting seeded successfully.');
        } else {
            $this->command->warn('Highly voted deal setting already exists.');
        }
    }
}
