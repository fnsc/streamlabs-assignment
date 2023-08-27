<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StreamEventsControllerTest extends TestCase
{
    use DatabaseMigrations;

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

    /**
     * @dataProvider getStreamEventsScenarios
     */
    public function testShouldUpdateStreamEventStatus(array $body, string $expectedTable): void
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
        $result = $this->postJson('/api/v1/update-event', $body);

        // Assertions
        $result->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas($expectedTable, ['read_status' => 1]);
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

    public static function getStreamEventsScenarios(): array
    {
        return [
            'update merch status' => [
                'body' => [
                    'id' => 1,
                    'status' => 1,
                    'type' => 'merch_sale',
                ],
                'expected_table' => 'merch_sales',
            ],
            'update donations status' => [
                'body' => [
                    'id' => 1,
                    'status' => 1,
                    'type' => 'donation',
                ],
                'expected_table' => 'donations',
            ],
            'update subscribers status' => [
                'body' => [
                    'id' => 1,
                    'status' => 1,
                    'type' => 'subscriber',
                ],
                'expected_table' => 'subscribers',
            ],
        ];
    }
}
