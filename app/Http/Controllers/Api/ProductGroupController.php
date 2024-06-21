<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductGroupRequest;

class ProductGroupController extends Controller
{
    public function store(ProductGroupRequest $request)
    {
        $createdCount = 0;
        $updatedCount = 0;

        foreach ($request->all() as $item) {
            $productGroup = ProductGroup::where('code_1C', $item['code_1C'])->first();
            if ($productGroup) {
                $productGroup->update($item);
                $updatedCount++;
            } else {
                ProductGroup::create($item);
                $createdCount++;
            }
        }

        $response = [
            'status' => 'success',
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
        ];

        Log::channel('product_groups')->info('Додано: ' . $createdCount . ', оновлено: ' . $updatedCount);

        return response()->json($response, 200);
    }
}
