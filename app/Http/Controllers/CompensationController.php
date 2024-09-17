<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use App\Enums\WarrantyClaimStatusEnum;
use App\Models\WarrantyClaimSpareParts;

class CompensationController extends Controller
{
    public function noDescription()
    {
        $warrantyClaims = WarrantyClaim::where('status', '=', WarrantyClaimStatusEnum::approved)->get();
        return view('app.documentations.tabs.nodescription', compact('warrantyClaims'));
    }

    public function service()
    {
        $warrantyClaims = WarrantyClaim::where('status', '=', WarrantyClaimStatusEnum::approved)->get();
        return view('app.documentations.tabs.service', compact('warrantyClaims'));
    }

    public function document()
    {
        $warrantyClaims = WarrantyClaim::where('status', '=', WarrantyClaimStatusEnum::approved)->get();

        foreach ($warrantyClaims as $claim) {
            // Считаем общие данные по запчастям
            $claim->total_spare_parts_qty = $claim->spareParts->sum('qty');
            $claim->total_spare_parts_cost = $claim->spareParts->sum('price');
            
            // Считаем общие данные по сервисным работам
            $claim->total_service_works_qty = $claim->serviceWorksAPI->sum('qty');
            $claim->total_service_works_cost = $claim->serviceWorksAPI->sum('price');
            
            // Общая сумма для этого сервисного центра
            $claim->total_cost = $claim->total_spare_parts_cost + $claim->total_service_works_cost;
        }

        return view('app.documentations.tabs.document', compact('warrantyClaims'));
    }
}
