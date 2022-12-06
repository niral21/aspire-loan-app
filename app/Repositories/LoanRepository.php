<?php

namespace App\Repositories;

use App\Http\Resources\Loan\WeeklyRepayResource;
use App\Interfaces\LoanRepositoryInterface;
use App\Models\Loan;
use App\Models\Outstanding;
use App\Models\User;
use App\Models\WeeklyRepay;
use App\Services\LoanService;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LoanRepository implements LoanRepositoryInterface
{
    use ApiResponser;
    public function newLoanRequest($request)
    {
        $user = User::find(auth()->user()->id);
        $principal = $request->input('principal');
        $interestRate = (new LoanService())->calculateInterest($principal);
        $weeksToRepay = $request->input('weeksToRepay');
        $repayAmount = (new LoanService())->calculateAmount($principal, $weeksToRepay, $interestRate);
        $ewi = $repayAmount / $weeksToRepay;
        $loan = Loan::create([
            'user_id' => $user->id,
            'principal' => $principal,
            'interest' => $interestRate,
            'weeksToRepay' => $weeksToRepay,
            'repayAmount' => $repayAmount,
            'ewi' => $ewi
        ]);
        return $this->successResponse($loan,'Loan Request made successfully. Your loan status is Pending.', Response::HTTP_CREATED);
    }

    public function approveLoan($request)
    {
        $user = User::find(auth()->user()->id);
        if($user->is_admin){
            $loan = Loan::where('id', $request->input('loan_id'))->update([
                'status' => 2
            ]);
            return $this->successResponse($loan,'Loan Approved successfully', Response::HTTP_CREATED);
        }else{
            return $this->errorResponse('Unauthorized user', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function showLoan()
    {
        $user = User::find(auth()->user()->id);
        $loan = Loan::where('user_id', $user->id)->get();
        if(!$loan){
            return $this->errorResponse('You don\'t have loan.', Response::HTTP_OK);
        }
        $loan = $loan->map(function ($item) {
            $item['status'] = Loan::LoanStatus[$item['status']];
            return $item;
        });
        return $this->successResponse($loan,'You\'ve below policies.', Response::HTTP_OK);
    }
    
    public function weeklyRepay($request)
    {
        $user = User::find(auth()->user()->id);
        $loan = Loan::where('id', $request->input('loan_id'))->where('user_id', $user->id)->first();
        if(!$loan || $loan->user_id != $user->id){
            return $this->errorResponse('You don\'t have loan.', Response::HTTP_OK);
        }
        if($loan->status < 2){
            return $this->errorResponse('Your loan is not Approved yet.', Response::HTTP_OK);   
        }
        if($loan->status > 3){
            return $this->errorResponse('Your\'ve already paid all your outstanding repayments.', Response::HTTP_OK);   
        }
        $payable_amount = $request->input('payable_amount');
        $paid_by = $user->id;
        $loan_id = $request->input('loan_id');
        $remaining_repay = $loan->repayAmount - $loan->totalRepaid;
        if($payable_amount > $remaining_repay) {
            return $this->errorResponse('You\'ve '.$remaining_repay.' outstanding repayments.', Response::HTTP_OK);   
        }
        if($remaining_repay >= $loan->ewi && $payable_amount < $loan->ewi){
            // return $this->errorResponse('You\'ve to pay minimum '.$loan->ewi.' for your loan repayments.', Response::HTTP_OK);   
        }
        $weeklyRepays = new WeeklyRepay();
        $weeklyRepays->loan_id = $loan_id;
        $weeklyRepays->payable_amount = $payable_amount;
        $weeklyRepays->paid_by = $paid_by;
        if($weeklyRepays->save()){
             $total_repaid = $loan->totalRepaid + $payable_amount;
             $is_paid = ($total_repaid == $loan->repayAmount) ? 4 : $loan->status; 
             Loan::where('id', $loan_id)->update([
                'totalRepaid' => $total_repaid,
                'status' => $is_paid
            ]);
            $weeklyRepays->outstanding_repayments = $loan->repayAmount-$total_repaid;
            return $this->successResponse(WeeklyRepayResource::make($weeklyRepays),'Weekly repayed successfully.', Response::HTTP_CREATED);
        }
    }
}
