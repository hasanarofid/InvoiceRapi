<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SynbarraSeeder extends Seeder
{
    /**
     * Run the database seeds for Synbarra Technologies.
     * Complies with PRD Version 1.0 (synbarra.com).
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@synbarra.com'],
            [
                'name' => 'Synbarra Administrator',
                'password' => Hash::make('SynbarraSecure2026!'),
                'email_verified_at' => now(),
            ]
        );

        // Assign superadmin role if Spatie Permission is enabled
        if (method_exists($admin, 'assignRole')) {
            try {
                $admin->assignRole('superadmin');
            } catch (\Throwable $e) {
                // Role might not exist yet, continue safely
            }
        }

        // 2. Seed Brand Configuration into Settings Table
        if (Schema::hasTable('settings')) {
            $brandSettings = [
                ['key' => 'brand_name', 'value' => 'SYNBARRA TECHNOLOGIES', 'type' => 'text'],
                ['key' => 'brand_tagline', 'value' => 'Engineering the Digital Future.', 'type' => 'text'],
                ['key' => 'brand_domain', 'value' => 'synbarra.com', 'type' => 'url'],
                ['key' => 'brand_email', 'value' => 'synbarr.techno@gmail.com', 'type' => 'text'],
                ['key' => 'brand_whatsapp', 'value' => '6288804958476', 'type' => 'text'],
                ['key' => 'brand_description', 'value' => 'SYNBARRA TECHNOLOGIES adalah penyedia solusi teknologi digital yang membantu bisnis dan organisasi mengembangkan sistem, website, serta aplikasi sesuai kebutuhan. Kami berfokus pada pengembangan teknologi yang praktis, efisien, dan dapat mendukung pertumbuhan bisnis.', 'type' => 'textarea'],
                ['key' => 'color_primary', 'value' => '#01232B', 'type' => 'text'],
                ['key' => 'color_primary_deep', 'value' => '#011E26', 'type' => 'text'],
                ['key' => 'color_secondary', 'value' => '#00F1DE', 'type' => 'text'],
                ['key' => 'color_accent', 'value' => '#04D2C5', 'type' => 'text'],
                ['key' => 'color_surface', 'value' => '#F8FAFA', 'type' => 'text'],
            ];

            foreach ($brandSettings as $setting) {
                Setting::updateOrCreate(
                    ['key' => $setting['key']],
                    ['value' => $setting['value'], 'type' => $setting['type']]
                );
            }
        }

        // 3. Seed Development & Client Acquisition Roadmap
        $this->call(RoadmapTaskSeeder::class);
    }
}
