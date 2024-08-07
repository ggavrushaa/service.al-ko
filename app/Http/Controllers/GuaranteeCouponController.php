<?php

namespace App\Http\Controllers;

use App\Models\GuaranteeCoupon;
use App\Models\Products;
use Illuminate\Http\Request;

class GuaranteeCouponController extends Controller
{

    public function index(Request $request)
    {
        $query = GuaranteeCoupon::with(['product.productPrices' => function ($query) {
            $query->select('product_id', 'recommended_price');
        }]);

        if($request->filled('barcode')) {
            $query->where('barcode', $request->barcode);
        }

        if($request->filled('factory_number')) {
            $query->where('factory_number', $request->factory_number);
        }

        $talons = $query->get();

        return view('app.search.index', compact('talons',))
            ->with('barcode', $request->barcode)
            ->with('factory_number', $request->factory_number);
    }
}
