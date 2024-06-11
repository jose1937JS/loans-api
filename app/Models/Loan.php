<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'name', 'reason', 'remaining_amount', 'amount_returned', 'estimated_refund_date', 'currency', 'ref_usd', 'rate'];
    protected $with = ['refunds'];

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}
