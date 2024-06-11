<?php
namespace App\Services;

use App\Models\Loan;
use App\Models\Refund;

class LoanService
{
    public function index()
    {
        $loans = Loan::all();
        return $loans;
    }

    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        return $loan;
    }

    public function store(array $data): Loan
    {
        $loan = Loan::create([
            'name' => $data['name'],
            'amount' => number_format($data['amount'], 2),
            'currency' => $data['currency'],
            'ref_usd' => number_format($data['ref_usd'], 2),
            'reason' => $data['reason'],
            'remaining_amount' => number_format($data['amount'], 2),
            'estimated_refund_date' => $data['estimated_refund_date'],
            'rate' => $data['rate'],
        ]);

        return $loan;
    }
}
