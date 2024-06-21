<?php
namespace App\Http\Controllers\Parts;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\WarrantyClaimSpareParts;

class PartsController extends Controller
{
    public function search($articul, $page = 1)
    {
        $perPage = 10;

        $parts = Products::where('articul', 'LIKE', "%{$articul}%")
                        ->with('productPrices')
                        ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($parts);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'warranty_claim_id' => 'required|integer',
                'line_number' => 'required|integer',
                'spare_parts' => 'required|integer',
                'qty' => 'required|integer',
                'price_without_vat' => 'required|numeric',
                'amount_without_vat' => 'required|numeric',
                'amount_vat' => 'required|numeric',
                'amount_with_vat' => 'required|numeric',
            ]);

            $warrantyClaimExists = WarrantyClaim::on('second_db')->where('id', $data['warranty_claim_id'])->exists();

            if (!$warrantyClaimExists) {
                return response()->json(['error' => 'Warranty claim not found'], 404);
            }

            $productExists = Products::on('mysql')->where('articul', $data['spare_parts'])->exists();

            if (!$productExists) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            WarrantyClaimSpareParts::create($data);

            return response()->json(['message' => 'Запчасть добавлена']);
        } catch (\Exception $e) {
            Log::error('Error adding spare part: ' . $e->getMessage());
            return response()->json(['error' => 'Error adding spare part'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $part = WarrantyClaimSpareParts::findOrFail($id);
            $part->delete();

            return response()->json(['success' => true, 'message' => 'Запчасть удалена']);
        } catch (\Exception $e) {
            Log::error('Error deleting spare part: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error deleting spare part'], 500);
        }
    }
}
