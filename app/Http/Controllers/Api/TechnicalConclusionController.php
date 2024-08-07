<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TechnicalConclusionRequest;
use App\Models\TechnicalConclusion\TechnicalConclusion;

class TechnicalConclusionController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');

        if (!$dateFrom) {
            return response()->json(['error' => 'date_from parameter is required'], 400);
        }
    
        $technicalConclusions = TechnicalConclusion::where('updated_at', '>', $dateFrom)
            ->with(['warrantyClaim', 'defectCode', 'symptomCode']) // Загружаем связанные данные
            ->get();
    
        // Подготовка данных для ответа
        $response = $technicalConclusions->map(function ($conclusion) {
            return [
                'id' => $conclusion->id,
                'code_1C' => $conclusion->code_1C,
                'warranty_claim_code_1C' => optional($conclusion->warrantyClaim)->code_1C,
                'defect_code_1C' => optional($conclusion->defectCode)->code_1C,
                'symptom_code_1C' => optional($conclusion->symptomCode)->code_1C,
                'conclusion' => $conclusion->conclusion,
                'resolution' => $conclusion->resolution,
                'date' => $conclusion->date,
                'created_at' => $conclusion->created_at,
                'updated_at' => $conclusion->updated_at,
            ];
        });
    
        Log::channel('technical_conclusions')->info('Запрос на эндпоинт /technical-conclusions - получение всех обновленных актов');
    
        return response()->json([
            'updated_records_count' => $technicalConclusions->count(),
            'updated_data' => $response,
        ], 200);
    }
    

    public function store(TechnicalConclusionRequest $request)
    {
        $technicalConclusions = $request->all();
        $createdCount = 0;
        $updatedCount = 0;
        $results = [];
    
        foreach ($technicalConclusions as $data) {
            $technicalConclusion = TechnicalConclusion::updateOrCreate(
                ['code_1C' => $data['code_1C']],
                $data
            );
    
            if ($technicalConclusion->wasRecentlyCreated) {
                $createdCount++;
                $action = 'Создано';
            } else {
                $updatedCount++;
                $action = 'Оновлено';
            }
    
            $results[] = [
                'message' => $action,
                'record_id' => $technicalConclusion->id,
                'data' => $technicalConclusion,
            ];
    
            Log::channel('technical_conclusions')->info("$action техническое заключение", ['id' => $technicalConclusion->id]);
        }
    
        return response()->json([
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
            'results' => $results,
        ], 200);
    }

}
