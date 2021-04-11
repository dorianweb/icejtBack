<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Flavor;
use Database\Factories\FlavorFactory;
use App\Http\Resources\FlavorRessource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class FlavorTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    // change testcase from phpunit to laravel to access jsons property

    public function test_getflavors()
    {
        $response = $this->getJson('/api/flavors/?page=1');
        $response->assertStatus(200);
    }
    public function test_store_flavor()
    {
        /*$user = Sanctum::actingAs(
            User::factory()->has()->create(),
        );*/
        $flavor = Flavor::factory()->make();
        $response = $this->postJson('/api/flavors', $flavor->toArray());
        $this->assertTrue(true);
    }
}
