<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PoliciesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    /*
        test to get policies without authentication
        expected return 401 with message Unauthorized.
    */
    public function testShowPoliciesWithoutLogin()
    {
        $this->json('POST', '/api/v1/users/loan/show-loan')
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized or Token expired']);
    }
}