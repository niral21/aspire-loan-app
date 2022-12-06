<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void

     */
    /*
        test to register without name, email and password
        expected return 422 
    */
    public function testWithoutNameEmailAndPassword()
    {
        $this->json('POST', '/api/v1/users/register')
            ->assertStatus(422)
            ->assertJson([
                'errors' =>
                [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    /*
        test to register with name, email and password
        expected return 201
    */
    public function testWithNameEmailAndPassword()
    {
        $user = User::factory()->create();
        $payload = [
            'name' => $user->name, 
            'email' => 'newuser'.rand().'@gmail.com', 
            'password' => 123456,
            'password_confirmation' => 123456
        ];
        $this->json('POST', '/api/v1/users/register', $payload)
            ->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => "User created successfully"
        ]);
    }

    /*
        test to register with password mismatch
        expected return 422 
    */
    public function testPasswordConfirmPasswordNotMatch()
    {
        $user = User::factory()->create();
        $payload = [
            'name' => $user->name, 
            'email' => 'newuser'.rand().'@gmail.com', 
            'password' => bcrypt(123456),
            'password_confirmation' => bcrypt(123456)
        ];

        $this->json('POST', '/api/v1/users/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' =>
                [
                    'password' => ['The password confirmation does not match.'],
                ]
        ]);
    }
}