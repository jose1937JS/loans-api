<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoanStoreRequest;
use App\Http\Resources\LoanResource;
use App\Services\LoanService;

class LoanController extends Controller
{
    protected $loanService;
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = $this->loanService->index();
        return LoanResource::collection($loans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoanStoreRequest $request)
    {
        $loan = $this->loanService->store($request->safe()->all());
        return LoanResource::make($loan);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = $this->loanService->show($id);
        return LoanResource::make($loan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
