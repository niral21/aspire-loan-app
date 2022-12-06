<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LoanTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /*
        test to submit loan without authentication
        expected return 401 with message Unauthorized.
    */
    public function testSubmitLoanWithoutAuthentication()
    {
        $this->json('POST', '/api/v1/users/loan/new-loan-request')
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized or Token expired']);
    }

    /*
        test to submit loan without principal and weeksToRepay
        expected return 422 
    */
    public function testSubmitLoanWithoutPrincipalWeeksToRepay()
    {
        $payload = [
            'email' => 'admin@mail.com', 
            'password' => '123456'
        ];
        $user = $this->json('POST', '/api/v1/users/login', $payload)
            ->assertStatus(200);
        $token = $user['data']['token'];

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->json('POST', '/api/v1/users/loan/new-loan-request')
            ->assertStatus(422)
            ->assertJson(['errors' =>
                [
                    'principal' => ['The principal field is required.'],
                    'weeksToRepay' => ['The weeks to repay field is required.'],
                ]
            ]);
    }

    /*
        test to submit loan with principal and weeksToRepay
        expected return 201 
    */
    public function testSubmitLoanWithPrincipalWeeksToRepay()
    {
        $payload = [
            'email' => 'admin@mail.com', 
            'password' => '123456'
        ];
        $user = $this->json('POST', '/api/v1/users/login', $payload)
            ->assertStatus(200);
        $token = $user['data']['token'];

        $payload = [
            'principal' => 11000, 
            'weeksToRepay' => 3,
        ];
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->json('POST', '/api/v1/users/loan/new-loan-request', $payload)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Loan Request made successfully. Your loan status is Pending.',
                'status' => true
            ]);
    }

    /*
        test to submit loan with principal value less than 10000 
        expected return 422 
    */
    public function testSubmitLoanWithPrincipalLessThan10000()
    {
        $payload = [
            'email' => 'admin@mail.com', 
            'password' => '123456'
        ];
        $user = $this->json('POST', '/api/v1/users/login', $payload)
            ->assertStatus(200);
        $token = $user['data']['token'];

        $payload = [
            'principal' => 1000, 
            'weeksToRepay' => 3,
        ];
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->json('POST', '/api/v1/users/loan/new-loan-request', $payload)
            ->assertStatus(422)
            ->assertJson(['errors' =>
                [
                    'principal' => ['The principal must be at least 10000.'],
                ]
            ]);
    }
}

