<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoanService{


    //Calculate rate of interest
    public function calculateInterest($principal)
    {
        if($principal >= 10000 && $principal <= 100000){
            return 8;
        }

        if($principal > 100000 && $principal <= 500000){
            return 7;
        }

        if($principal > 500000){
            return 6;
        }
    }

    //Calculate amount
    public function calculateAmount($principal, $weeksToRepay, $interestRate)
    {
        $applicationFee = 500;
        $interestPerWeek = ($principal/100) * $interestRate;
        $totalInterest = $interestPerWeek * $weeksToRepay;
        return $totalInterest + $applicationFee + $principal;
    }
}
