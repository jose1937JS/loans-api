<?php
namespace App\Services;

use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundService
{
    private function validate($params)
    {
        //
    }

    public function store(array $data): Refund | string
    {
        try {
            DB::beginTransaction();

                // Seleccionar el ultimo retorno del prestamo
            $early_refund = Refund::where('loan_id', $data['loan_id'])->orderBy('id', 'desc')->first();

            // Validaciones
            if($early_refund) {
                // Validar que no se puedan agregar mas retornos si la deuda está saldada
                if($early_refund->loan->remaining_amount == 0) {
                    return 'La deuda ya ha sido saldada';
                }

                if($data['currency'] == $early_refund->loan->currency) {
                    // Validar que no se pague un monto mayor al que se debe usando la misma moneda
                    if($data['amount'] > $early_refund->loan->remaining_amount) {
                        return "El monto a pagar supera la deuda restante. Se deben: {$early_refund->loan->remaining_amount} {$early_refund->loan->currency}";
                    }
                }
                else {
                    // Prestamo en USD y devolucion en VES
                    if($early_refund->loan->currency == 'USD' && $data['currency'] == 'VES') {
                        $refund_in_dollars = $data['amount'] / $early_refund->loan->rate;

                        if($refund_in_dollars > $early_refund->loan->remaining_amount) {
                            return "El monto a pagar supera la deuda restante. Se deben: {$early_refund->loan->remaining_amount} {$early_refund->loan->currency}";
                        }
                    }

                    // Prestamo en VES y devolucion en USD
                    if($early_refund->loan->currency == 'VES' && $data['currency'] == 'USD') {
                        $refund_in_ves = $data['amount'] * $early_refund->loan->rate;

                        if($refund_in_ves > $early_refund->loan->remaining_amount) {
                            return "El monto a pagar supera la deuda restante. Se deben: {$early_refund->loan->remaining_amount} {$early_refund->loan->currency}";
                        }
                    }
                }
            }

            // Crear la devolucion
            $refund = Refund::create([
                'amount' => number_format($data['amount'], 2),
                'currency' => $data['currency'],
                'ref_usd' => number_format($data['ref_usd'], 2),
                'loan_id' => $data['loan_id'],
                'rate' => $data['rate'],
            ]);

            // Restar la devolucion anterior con la que se está ingresando (tener en cuenta las monedas)

            $amount_returned = 0;
            $remaining_amount = 0;
            $refund_in_loan_currency = $data['amount'];

            // CAlculo del Monto en VES
            if($data['currency'] == 'USD' && $refund->loan->currency == 'VES') {
                $refund_in_loan_currency = $data['amount'] * $refund->loan->rate;
            }

            // Calculo del Monto en USD
            if($data['currency'] == 'VES' && $refund->loan->currency == 'USD') {
                $refund_in_loan_currency = $data['amount'] / $refund->loan->rate;
            }

            if($early_refund) {
                $amount_returned = $early_refund->amount + $refund_in_loan_currency;
            }
            else {
                $amount_returned = $refund->amount + $refund_in_loan_currency;
            }

            $remaining_amount = $refund->loan->remaining_amount - $refund_in_loan_currency;

            // Actualizar la entidad del prestamo con el monto restante por pagar y lo que se ha pagado hasta ahora
            $refund->loan->update([
                'remaining_amount' => number_format($remaining_amount, 2),
                'amount_returned' => number_format($amount_returned, 2)
            ]);

            DB::commit();

            return $refund;
        }
        catch (\Exception  $e) {
            DB::rollBack();
            Log::debug($e);

            return "Ha habido un error: {$e->getMessage()}";
        }
    }

    public function show($id)
    {
        $refund = Refund::findOrFail($id);
        return $refund;
    }
}
