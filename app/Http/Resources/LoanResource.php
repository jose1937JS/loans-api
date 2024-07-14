<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => number_format($this->amount, 2, '.', ''),
            'currency' => $this->currency,
            'ves_exchange' => number_format($this->ves_exchange, 2, '.', ''),
			'description' => $this->description,
			'rate' => number_format($this->rate, 2, '.', ''),
			'remaining_amount' => number_format($this->remaining_amount, 2, '.', ''),
			'amount_returned' => number_format($this->amount_returned, 2, '.', ''),
			'estimated_refund_date' => $this->estimated_refund_date,
            'created_at' => date_format($this->created_at, 'Y-m-d H:i:s'),
            'refunds' => RefundResource::collection($this->whenLoaded('refunds')),
        ];
    }
}
