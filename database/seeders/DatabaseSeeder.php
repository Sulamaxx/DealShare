<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        /* $this->call([
            SettingSeeder::class,
            TopBannerSettingSeeder::class,
            BottomBannerSettingSeeder::class,
            HighlyVotedDealSettingSeeder::class,
        ]);

        User::factory()->withPersonalTeam()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'status' => 1,
            'user_type' => 'admin'
        ]); */

        $this->call([
            SettingSeeder::class,
            TopBannerSettingSeeder::class,
            BottomBannerSettingSeeder::class,
            HighlyVotedDealSettingSeeder::class,
        ]);

        $adminEmail = env('ADMIN_EMAIL', 'sys@buyme.lk');
        $adminPassword = env('ADMIN_PASSWORD', 'By1290me~');

        $adminUser = User::where('user_type', 'admin')->first(); // Find the admin by type

        if ($adminUser) {
            // Update email if it's different from .env
            if ($adminUser->email !== $adminEmail) {
                $adminUser->email = $adminEmail;
                $adminUser->save();
                Log::info("Updated admin user email to: {$adminEmail}");
            }
            // Always update password, as hashes change
            $adminUser->password = Hash::make($adminPassword);
            $adminUser->save();
            Log::info("Updated admin user password for: {$adminEmail}");
        } else {
            // Create the admin if not found (first time seeding)
            User::create([
                'name' => 'Admin',
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'status' => 1,
                'user_type' => 'admin'
            ]);
            Log::info("Created initial admin user: {$adminEmail}");
        }
    }
}
