<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefundRequest;
use App\Http\Resources\RefundResource;
use App\Models\Refund;
use App\Services\RefundService;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    protected $refundService;

    public function __construct(RefundService $refundService)
    {
        $this->refundService = $refundService;
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
}
