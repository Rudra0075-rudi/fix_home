<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            ['name' => 'Trusted Pro', 'description' => 'Verified and trusted professional', 'color' => '#3b82f6'],
            ['name' => 'Punctual', 'description' => 'Always on time', 'color' => '#10b981'],
            ['name' => 'Highly Rated', 'description' => 'Consistently receives 5-star ratings', 'color' => '#f59e0b'],
            ['name' => 'Top Performer', 'description' => 'Exceeds expectations', 'color' => '#8b5cf6'],
            ['name' => 'Reliable', 'description' => 'Dependable and consistent', 'color' => '#06b6d4'],
            ['name' => 'Expert', 'description' => 'Master of their craft', 'color' => '#ef4444'],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(
                ['name' => $badge['name']],
                [
                    'description' => $badge['description'],
                    'color' => $badge['color'],
                    'is_active' => true,
                ]
            );
        }
    }
}
