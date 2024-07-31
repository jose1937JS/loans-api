<?php
namespace App\Services;

use App\Models\Loan;
use App\Models\Refund;

class LoanService
{
    public function index()
    {
        $loans = Loan::orderBy('created_at', 'desc')->get();
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
            'amount' => number_format($data['amount'], 2, '.', ''),
            'currency' => $data['currency'],
            'ves_exchange' => number_format($data['ves_exchange'], 2, '.', ''),
            'description' => $data['description'],
            'remaining_amount' => number_format($data['amount'], 2, '.', ''),
            'estimated_refund_date' => $data['estimated_refund_date'],
            'rate' => $data['rate'],
            'rate_type' => $data['rate_type'],
        ]);

        return $loan;
    }
}
