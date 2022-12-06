<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    /*
        test to login without email and password
        expected return 422 with error Unprocessable Content
    */
    public function testLoginWithoutEmailPass()
    {
        $response = $this->json('POST','/api/v1/users/login');
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
    }

    /*
        test to login without password
        expected return 422 with error Unprocessable Content
    */
    public function testLoginWithoutPass()
    {
    	$user = User::factory()->create();
        $payload = ['email' => $user->email];
        $response = $this->json('POST','/api/v1/users/login', $payload);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
    }

    /*
        test to login with valid email and password
        expected return 200 with success message 
    */
    public function testLoginWithValidEmailPass()
    {
        $user = User::factory()->create();
        $payload = [
        	'email' => 'admin@mail.com', 
        	'password' => '123456'
        ];
        $this->json('POST', '/api/v1/users/login', $payload)
            ->assertStatus(200)
            ->assertJson([
                    'status' => true,
                    'message' => "Logged In successfully"
            ]);
    }

    /*
        test to login with invalid email and password
        expected return 401 with success message 
    */
    public function testLoginWithInvalidEmailPass()
    {
        $user = User::factory()->create();
        $payload = [
            'email' => $user->email, 
            'password' => $user->password
        ];
        $this->json('POST', '/api/v1/users/login', $payload)
            ->assertStatus(401)
            ->assertJson([
                    'status' => false,
                    'message' => "Invalid Credentials"
            ]);
    }
}