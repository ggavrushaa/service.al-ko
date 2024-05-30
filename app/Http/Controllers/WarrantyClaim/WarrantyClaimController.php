<?php

namespace App\Http\Controllers\WarrantyClaim;

use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use App\Http\Controllers\Controller;

class WarrantyClaimController extends Controller
{
    public function index()
    {
        $warrantyClaims = WarrantyClaim::with('user')->get();
        return view('app.warranty.index', compact('warrantyClaims'));
    }

    public function search(Request $request)
    {
        $query = WarrantyClaim::query();

        if($request->filled('barcode')) {
            $query->where('barcode', $request->barcode);
        }

        if($request->filled('number')) {
            $query->where('factory_number', $request->barcode);
        }

        $claims = $query->with('user')->get();

        return view('app.search.index', compact('claims'));
    }        
}
