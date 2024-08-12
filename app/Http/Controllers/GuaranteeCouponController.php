<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\GuaranteeCoupon;
use App\Enums\WarrantyClaimStatusEnum;
use App\Models\WarrantyClaim;

class GuaranteeCouponController extends Controller
{

    public function index(Request $request)
    {
        // Получаем талон с товаром и ценой
        $query = GuaranteeCoupon::with(['product.productPrices' => function ($query) {
            $query->select('product_id', 'recommended_price');
        }]);
    
        if ($request->filled('barcode')) {
            $query->where('barcode', $request->barcode)
                ->where('status', 'ACTIVE');
        }
    
        if ($request->filled('factory_number')) {
            $query->where('factory_number', $request->factory_number)
                ->where('status', 'ACTIVE');;
        }
    
        $talons = $query->get();
    
        if ($talons->isEmpty()) {
            // Обработка 
            return to_route('app.search')->withErrors('Не знайдено жодного талону');
        }
    
        if ($talons->count() === 1) {
            // Если найден только один талон
            $talon = $talons->first();
            return redirect()->route('app.warranty.create', [
                'barcode' => $talon->barcode,
                'factory_number' => $talon->factory_number,
            ]);
        }
    
        // Если найдено больше одного талона
        return view('app.search.index', compact('talons'))
            ->with('barcode', $request->barcode)
            ->with('factory_number', $request->factory_number);
    }
}
