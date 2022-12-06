<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'principal', 'interest', 'weeksToRepay', 'repayAmount', 'ewi'];
    
    public const LoanStatus = [
        1 => "PENDING", 
        2 => "APPROVED", 
        3 => "REJECTED", 
        4 => "PAID"
    ];

    public function weeklyRepays()
    {
        $this->hasMany(WeeklyRepay::class);
    }

}
