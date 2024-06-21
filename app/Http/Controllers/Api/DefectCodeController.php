<?php

namespace App\Http\Controllers\Api;

use App\Models\DefectCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DefectCodeRequest;

class DefectCodeController extends Controller
{
    public function store(DefectCodeRequest $request)
    {
        $createdCount = 0;
        $updatedCount = 0;

        foreach($request->all() as $item) {
            $defectCode = DefectCodes::where('code_1C', $item['code_1C'])->first();
            if($defectCode) {
                $defectCode->update($item);
                $updatedCount++;
            } else {
                DefectCodes::create($item);
                $createdCount++;
            }
        }

        $response = [
            'status' => 'success',
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
        ];

        Log::channel('defect_codes')->info('Додано: ' . $createdCount . ', оновлено: ' . $updatedCount);

        return response()->json($response, 200);
    }
}
