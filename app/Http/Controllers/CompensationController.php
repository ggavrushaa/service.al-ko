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

    public function documents()
    {
        $warrantyClaims = WarrantyClaim::where('status', '=', WarrantyClaimStatusEnum::approved)->get();
        return view('app.documentations.tabs.service', compact('warrantyClaims'));
    }
}
