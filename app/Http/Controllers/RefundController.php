<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefundRequest;
use App\Http\Resources\RefundResource;
use App\Models\Refund;
use App\Services\RefundService;
use Illuminate\Http\Request;

use App\Services\DollarApiService;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    protected $refundService;
    protected $dollarApiService;

    public function __construct(RefundService $refundService, DollarApiService $dollarApiService)
    {
        $this->refundService = $refundService;
        $this->dollarApiService = $dollarApiService;
    }

    public function index()
    {

    }

    public function show($id)
    {
        $refund = $this->refundService->show($id);
        return RefundResource::make($refund);
    }

    public function store(RefundRequest $request)
    {
        $refund = $this->refundService->store($request->safe()->all());

        if($refund instanceof Refund) {
            return RefundResource::make($refund);
        }

        return response()->json(['message' => $refund]);
    }

    public function update(Request $request, $id)
    {

    }

    public function delete($id)
    {

    }

    // For testing purposes
    public function dollar()
    {
        $res = $this->dollarApiService->get();
        return $res->json();
    }
}
