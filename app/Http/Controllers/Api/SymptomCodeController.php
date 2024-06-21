<?php

namespace App\Http\Controllers\Api;

use App\Models\SymptomCodes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SymptomCodeRequest;
use Illuminate\Support\Facades\Log;

class SymptomCodeController extends Controller
{
    public function store(SymptomCodeRequest $request)
    {
        $createdCount = 0;
        $updatedCount = 0;

        foreach($request->all() as $item) {
            $symptomCode = SymptomCodes::where('code_1C', $item['code_1C'])->first();
            if($symptomCode) {
                $symptomCode->update($item);
                $updatedCount++;
            } else {
                SymptomCodes::create($item);
                $createdCount++;
            }
        }

        $response = [
            'status' => 'success',
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
        ];

        Log::channel('symptom_codes')->info('Додано: ' . $createdCount . ', оновлено: ' . $updatedCount);

        return response()->json($response, 200);
        
    }
}
