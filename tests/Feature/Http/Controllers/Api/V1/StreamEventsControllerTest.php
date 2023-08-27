<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StreamEventsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldGetEventList(): void
    {
        // Set
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johnDoe@gmail.com'
        ]);
        Passport::actingAs($user);

        $this->setupSubscribersData($user);
        $this->setupDonationsData($user);
        $this->setupMerchData($user);

        // Action
        $result = $this->getJson('/api/v1/event-list');

        // Assertions
        $result->assertStatus(Response::HTTP_OK);
        $result->assertJsonFragment(['first_page_url' => 'http://localhost/api/v1/event-list?page=1']);
    }

    private function setupSubscribersData($user): void
    {
        DB::table('subscribers')->insert([
            'streamer_id' => $user->id,
            'name' => 'Name1',
            'tier' => 1,
            'created_at' => new DateTime('-10 days')
        ]);

        DB::table('subscribers')->insert([
            'streamer_id' => $user->id,
            'name' => 'Name3',
            'tier' => 3,
            'created_at' => new DateTime('-20 days')
        ]);
    }

    private function setupDonationsData($user): void
    {

        DB::table('donations')->insert([
            'streamer_id' => $user->id,
            'amount' => 100,
            'currency' => 'USD',
            'created_at' => new DateTime('-45 days')
        ]);

        DB::table('donations')->insert([
            'streamer_id' => $user->id,
            'amount' => 250.51,
            'currency' => 'USD',
            'created_at' => new DateTime('-29 days')
        ]);
    }

    private function setupMerchData($user): void
    {
        DB::table('merch_sales')->insert([
            'streamer_id' => $user->id,
            'name' => 'socks',
            'amount' => 2,
            'unit_price' => 5,
            'created_at' => new DateTime('-16 days')
        ]);

        DB::table('merch_sales')->insert([
            'streamer_id' => $user->id,
            'name' => 'socks',
            'amount' => 1,
            'unit_price' => 5,
            'created_at' => new DateTime('-45 days')
        ]);
    }
}
