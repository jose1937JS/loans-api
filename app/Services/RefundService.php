<?php
namespace App\Services;

use App\Models\Loan;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundService
{
    // private $dollarApiService;

    // public function __construct(DollarApiService $dollarApiService)
    // {
    //     $this->dollarApiService = $dollarApiService;
    // }

    private function validate($data, $loan)
    {
        // Validaciones
        $remaining_amount_ves = $loan->remaining_amount * $data['rate'];

        // Validar que no se puedan agregar mas retornos si la deuda está saldada
        if($loan->remaining_amount == 0) {
            return 'La deuda ya ha sido saldada';
        }

        if($data['amount'] > $loan->remaining_amount) {
            // $remaining_amount_ves = $loan->remaining_amount * $data['rate'];
            return "El monto a pagar supera la deuda restante. Se deben: {$loan->remaining_amount} USD o {$remaining_amount_ves} VES";
        }

        if($data['amount'] > $loan->remaining_amount) {
            return "El monto a pagar supera la deuda restante. Se deben: {$loan->remaining_amount} USD o {$remaining_amount_ves} VES";
        }
    }

    public function store(array $data): Refund | string
    {
        try {
            DB::beginTransaction();

            // Seleccionar el prestamo
            $loan = Loan::find($data['loan_id']);

            $this->validate($data, $loan);

            // Crear la devolucion
            $refund = Refund::create([
                'amount' => number_format($data['amount'], 2, '.', ''),
                'currency' => $data['currency'],
                'ves_exchange' => number_format($data['ves_exchange'], 2, '.', ''),
                'loan_id' => $data['loan_id'],
                'rate' => $data['rate'],
            ]);

            $amount_returned  = $loan->amount_returned + $data['amount'];
            $remaining_amount = $loan->remaining_amount - $data['amount'];

            // Actualizar la entidad del prestamo con el monto restante por pagar y lo que se ha pagado hasta ahora
            $loan->update([
                'remaining_amount' => number_format($remaining_amount, 2, '.', ''),
                'amount_returned' => number_format($amount_returned, 2, '.', '')
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
