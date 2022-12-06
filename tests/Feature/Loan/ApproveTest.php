<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ApproveTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /*
        test to check only admin can approve loan
        expected return 401
    */
    public function testUserCannotApproveLoan()
    {   
        // create user
        $user = User::factory()->create();
        $payload = [
            'name' => $user->name, 
            'email' => 'newuser'.rand().'@gmail.com', 
            'password' => 123456,
            'password_confirmation' => 123456
        ];
        $user = $this->json('POST', '/api/v1/users/register', $payload)
            ->assertStatus(201);

        // user login
        $payload = [
            'email' => $user['data']['email'], 
            'password' => '123456'
        ];
        $user = $this->json('POST', '/api/v1/users/login', $payload)
            ->assertStatus(200);
        $token = $user['data']['token'];
        
        // submit loan request
        $payload = [
            'principal' => 15000, 
            'weeksToRepay' => 3,
        ];
        $loan = $this->withHeader('Authorization', 'Bearer '.$token)
            ->json('POST', '/api/v1/users/loan/new-loan-request', $payload)
            ->assertStatus(201);

        // approve loan request
        $loan_id = $loan['data']['id'];
        $payload = [
            'loan_id' => $loan_id
        ];
        $approve = $this->withHeader('Authorization', 'Bearer '.$token)
            ->json('POST', '/api/v1/users/admin/loan/approve-loan', $payload)
            ->assertStatus(401);
        
    }
}

