<?php

namespace App\Http\Controllers;

use App\Models\Contract;

class ContractController extends Controller
{
    public function getContractsByServiceCenter($centerId)
    {
        $contracts = Contract::where('partner_id', $centerId)
                            ->where('contract_type', 'Сервис')
                            // ->where('order_type_id', 3)
                            ->orderBy('added_time', 'desc')
                            ->get();
                            
        return response()->json($contracts);
    }
}
