<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
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
            'amount' => number_format($this->amount, 2),
            'currency' => $this->currency,
            'ref_usd' => number_format($this->ref_usd, 2),
            'rate' => number_format($this->rate, 2),
            'created_at' => date_format($this->created_at, 'Y-m-d H:i:s')
        ];
    }
}
