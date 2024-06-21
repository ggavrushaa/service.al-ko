<?php

namespace App\Http\Controllers\WarrantyClaim;

use App\Models\ProductGroup;
use App\Models\ServiceWorks;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use App\Http\Controllers\Controller;
use App\Models\WarrantyClaimSpareParts;

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

        if($request->filled('factory_number')) {
            $query->where('factory_number', $request->factory_number);
        }

        $claims = $query->with('user')->get();

        return view('app.search.index', compact('claims'))
            ->with('barcode', $request->barcode)
            ->with('factory_number', $request->factory_number);
    }        

    public function edit($id)
    {
        $claim = WarrantyClaim::with(['spareParts.product', 'user'])->find($id);

        if(!$claim) {
            return redirect()->route('app.warranty.index')->with('error', 'Warranty claim not found');
        }

        $groups = ProductGroup::all();
        $works = ServiceWorks::all();
        $spareParts = $claim->spareParts; 
        $total = $spareParts->sum('amount_with_vat');

        return view('app.warranty.edit', compact('claim', 'groups', 'works', 'spareParts', 'total'));
    }

    public function getParts($id)
    {
        $parts = WarrantyClaimSpareParts::with('product')
                    ->where('warranty_claim_id', $id)
                    ->get();

        return response()->json($parts);
    }
}
