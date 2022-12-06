<?php

namespace App\Http\Controllers\API\v1\Loan;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\Loan\ApproveLoanRequest;
use App\Http\Requests\Loan\NewLoanRequest;
use App\Http\Requests\Loan\WeeklyRepayRequest;
use App\Interfaces\LoanRepositoryInterface;
use Illuminate\Http\JsonResponse;

class LoanController extends APIController
{
    private LoanRepositoryInterface $loanRepository;
    public function __construct(LoanRepositoryInterface $loanRepository)
    {
            $this->loanRepository = $loanRepository;
    }

    public function newLoanRequest(NewLoanRequest $request): JsonResponse
    {
        return $this->loanRepository->newLoanRequest($request);
    }

    public function approveLoan(ApproveLoanRequest $request): JsonResponse
    {
        return $this->loanRepository->approveLoan($request);
    }

    public function showLoan(): JsonResponse
    {
        return $this->loanRepository->showLoan();
    }

    public function weeklyRepay(WeeklyRepayRequest $request): JsonResponse
    {
        return $this->loanRepository->weeklyRepay($request);
    }
}
