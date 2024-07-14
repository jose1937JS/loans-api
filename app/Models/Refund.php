<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'loan_id', 'currency', 'ves_exchange', 'rate'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
