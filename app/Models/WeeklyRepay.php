<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyRepay extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id', 'payable_amount', 'paid_by'
    ];

    public function loan()
    {
        $this->belongsTo(Loan::class, 'loan_id', 'id');
    }
}
