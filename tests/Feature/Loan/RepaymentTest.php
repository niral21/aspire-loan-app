<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RepaymentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /*
        test to submit repayment after loan approved
        expected return 201 with message repayed successfully.
    */
    public function testSubmitLoanWithoutPrincipalWeeksToRepay()
    {   
        // user login
        $payload = [
            'email' => 'admin@mail.com', 
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
            ->assertStatus(201);

        // weekly-repay request
        $payload = [
            'loan_id' => $loan_id,
            'payable_amount' => $loan['data']['ewi']
        ];
        $loan = $this->withHeader('Authorization', 'Bearer '.$token)
            ->json('POST', '/api/v1/users/loan/weekly-repay', $payload)
            ->assertStatus(201)
            ->assertJson([
                'status' => true,
            ]);
        
    }
}

