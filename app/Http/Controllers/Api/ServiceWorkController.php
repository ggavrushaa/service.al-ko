<?php

namespace App\Http\Controllers\Api;

use App\Models\ServiceWorks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ServiceWorkController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            '*.id' => 'nullable|integer',
            '*.code_1C' => 'required|string|max:40',
            '*.name' => 'required|string|max:200',
            '*.product_group_id' => 'required',
            '*.duration_decimal' => 'required|numeric',
            '*.duration_minutes' => 'required|integer',
        ]);

        $createdCount = 0;
        $updatedCount = 0;  

        foreach ($validatedData as $data) {
            $serviceWork = ServiceWorks::updateOrCreate(
                ['code_1C' => $data['code_1C']],
                $data
            );

            if ($serviceWork->wasRecentlyCreated) {
                $createdCount++;
            } else {
                $updatedCount++;
            }
        }

        Log::channel('service_works')->info('Запрос на эндпоінт /service-works - обробка даних от 1С', [
            'new_records_count' => $createdCount,
            'updated_records_count' => $updatedCount,
        ]);

         return response()->json([
            'status' => 'success',
            'new_records_count' => $createdCount,
            'updated_records_count' => $updatedCount,
        ], 200);
    }

}
