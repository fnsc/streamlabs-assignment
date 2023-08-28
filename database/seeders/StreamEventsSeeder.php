<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StreamEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $this->setupFollowers($user);
        $this->setupSubscribers($user);
        $this->setupDonations($user);
        $this->setupMerch($user);
    }

    private function setupFollowers(User $user): void
    {
        $followers = rand(300, 500);
        for ($i = 0; $i < $followers; $i++) {
            DB::table('followers')->insert([
                'streamer_id' => $user->id,
                'name' => fake()->name,
                'created_at' => fake()->dateTimeBetween('-90 days'),
            ]);
        }
    }

    private function setupSubscribers(User $user): void
    {
        $subscribers = rand(300, 500);
        for ($i = 0; $i < $subscribers; $i++) {
            DB::table('subscribers')->insert([
                'streamer_id' => $user->id,
                'name' => fake()->name,
                'tier' => fake()->randomElement([1, 2, 3]),
                'created_at' => fake()->dateTimeBetween('-90 days'),
            ]);
        }
    }

    private function setupDonations(User $user): void
    {
        $donations = rand(300, 500);
        for ($i = 0; $i < $donations; $i++) {
            DB::table('donations')->insert([
                'streamer_id' => $user->id,
                'currency' => 'USD',
                'amount' => fake()->randomFloat(2, 10, 10000),
                'message' => fake()->text(),
                'created_at' => fake()->dateTimeBetween('-90 days'),
            ]);
        }
    }

    private function setupMerch(User $user): void
    {
        $items = [
            'shirt',
            'jacket',
            'bottle',
            'socks',
        ];

        $itemsPrice = [
            'shirt' => 10.99,
            'jacket' => 99.99,
            'bottle' => 25.99,
            'socks' => 5.99,
        ];

        $merch = rand(300, 500);
        for ($i = 0; $i < $merch; $i++) {
            $name = fake()->randomElement($items);
            DB::table('merch_sales')->insert([
                'streamer_id' => $user->id,
                'name' => $name,
                'amount' => fake()->randomElement([1, 2, 3]),
                'unit_price' => $itemsPrice[$name],
                'created_at' => fake()->dateTimeBetween('-90 days'),
            ]);
        }
    }
}
