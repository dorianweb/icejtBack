<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    
    public function test_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret')
        ]);
        $response = $this->post('api/login', ['email' => $user->email, 'password' => 'secret']);
        $response->assertStatus(201);
    }
    public function test_auth_user()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(),
        );
        $response = $this->get('api/auth_user');
        $response->assertStatus(200);
    }
}
