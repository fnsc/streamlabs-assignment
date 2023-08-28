<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use App\Services\Followers\FollowersInput;
use App\Services\Followers\FollowersService;
use DateTime;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Mockery as m;
use Tests\TestCase;

class FollowersControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testShouldReturnTheNumberOfFollowersInThePast30Days(): void
    {
        // Set
        $user1 = User::create([
            'name' => 'Random User',
            'email' => 'randomUser@gmail.com'
        ]);

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johnDoe@gmail.com'
        ]);

        Passport::actingAs($user);

        $this->setupFollowers($user1, $user);

        // Action
        $result = $this->getJson('/api/v1/followers-count');

        // Assertions
        $result->assertStatus(Response::HTTP_OK);
        $result->assertContent('{"followers_count":2}');
    }

    public function testShouldReturn500WhenSomethingHappened(): void
    {
        // Set
        $service = $this->instance(FollowersService::class, m::mock(FollowersService::class));
        $user1 = User::create([
            'name' => 'Random User',
            'email' => 'randomUser@gmail.com'
        ]);

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johnDoe@gmail.com'
        ]);

        Passport::actingAs($user);

        $this->setupFollowers($user1, $user);

        // Expectations
        $service->expects()
            ->handle(m::type(FollowersInput::class))
            ->andThrow(Exception::class);

        // Action
        $result = $this->getJson('/api/v1/followers-count');

        // Assertions
        $result->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $result->assertContent('{"error":"Something unexpected has happened."}');
    }

    private function setupFollowers($user1, $user): void
    {
        DB::table('followers')->insert([
            'name' => 'Random 4',
            'streamer_id' => $user1->id,
            'created_at' => new DateTime('-5 days'),
        ]);

        DB::table('followers')->insert([
            'name' => 'Random 1',
            'streamer_id' => $user->id,
            'created_at' => new DateTime('-45 days'),
        ]);

        DB::table('followers')->insert([
            'name' => 'Random 2',
            'streamer_id' => $user->id,
            'created_at' => new DateTime('-20 days'),
        ]);

        DB::table('followers')->insert([
            'name' => 'Random 3',
            'streamer_id' => $user->id,
            'created_at' => new DateTime('-15 days'),
        ]);
    }
}
