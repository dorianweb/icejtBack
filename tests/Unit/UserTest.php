<?php

namespace Tests\Unit;

use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_get_users()
    {
        $response = $this->get('/api/users');
        $this->assertEquals(200, $response->status());
    }
    public function test_get_user()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(),
        );
        $response = $this->get('/api/users/' . $user->id);
        $this->assertEquals(200, $response->status());
    }

    public function test_update_user_code()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(),
        );
        $response = $this->put('/api/users/' . $user->id, [
            'email' => $user->email,
            'password' => $user->password,
            'name' => 'fekir'
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function test_user_update_name()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['password' => bcrypt('isOk')]),
        );
        $response = $this->json('PUT', '/api/users/' . $user->id, [
            'email' => $user->email,
            'password' => $user->password,
            'name' => 'fekir'
        ]);
        $response->assertJsonFragment([
            'name' => 'fekir'
        ]);
    }
    public function test_user_update_email()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['password' => bcrypt('isOk')]),
        );


        $email = $this->faker->email();
        $response = $this->json('PUT', '/api/users/' . $user->id, [
            'email' => $email,
            'password' => $user->password,
            'name' => $user->name,
        ]);
        $response->assertJsonFragment([
            'email' => $email,
        ]);
    }
    public function test_user_update_password_same()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['password' => bcrypt('isOk')]),
        );
        $response = $this->json('PUT', '/api/users/' . $user->id, [
            'email' => $user->email,
            'password' => $user->password,
            'name' => $user->name,
        ]);
        $user2 = User::find($user->id);
        $this->assertEquals($user2->password, $user->password, 'cheh');
    }

    public function test_user_update_password_different()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['password' => bcrypt('isOk')]),
        );
        $password = $this->faker->name();

        $response = $this->json('PUT', '/api/users/' . $user->id, [
            'email' => 'sample@example.com',
            'password' => $password,
            'name' => $user->name,
        ]);

        $user2 = User::find($user->id);
        $this->assertTrue(Hash::check($password, $user2->password));
    }

    public function test_store()
    {
        $email = $this->faker->email();
        $password = $this->faker->name();
        $name = $this->faker->name();
        $response = $this->postJson('/api/users', [

            'email' => $email,
            'password' => $password,
            'name' => $name

        ]);
        $response->assertStatus(201)
            ->assertJsonFragment(['created' => true])
            ->assertJsonFragment(['name' => $name])
            ->assertJsonFragment(['email' => $email]);
    }

    public function test_store_password()
    {
        $email = $this->faker->email();
        $password = $this->faker->name();
        $name = $this->faker->name();
        $response = $this->postJson('/api/users', [
            'email' => $email,
            'password' => $password,
            'name' => $name
        ]);
        $json = $response->decodeResponseJson();
        $this->assertTrue(Hash::check($password, $json['data']['password']));
    }

    public function test_delete_user()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['password' => bcrypt('isOk')]),
        );
        $response = $this->deleteJson('/api/users/' . $user->id);
        $json = $response->assertJsonFragment([
            'deleted' => true
        ]);
    }
}
