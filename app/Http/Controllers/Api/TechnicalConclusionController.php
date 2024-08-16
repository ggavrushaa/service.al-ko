<?php

namespace App\Http\Controllers\Api;

use App\Enums\WarrantyClaimStatusEnum;
use Carbon\Carbon;
use App\Models\DefectCodes;
use App\Models\SymptomCodes;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
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
                'warranty_claim_code_id' => optional($conclusion->warrantyClaim)->code_1C,
                'defect_code' => optional($conclusion->defectCode)->code_1C,
                'symptom_code' => optional($conclusion->symptomCode)->code_1C,
                'conclusion' => $conclusion->conclusion,
                'resolution' => $conclusion->resolution,
                'date' => $conclusion->date,
                'appeal_type' => $conclusion->appeal_type,
                'created_at' => Carbon::parse($conclusion->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($conclusion->updated_at)->format('Y-m-d H:i:s'),
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
        $errors = [];

        foreach ($technicalConclusions as $data) {
            try {
                // Ищем ID по коду 1С в таблице warranty_claims
                $warrantyClaim = WarrantyClaim::where('code_1C', $data['warranty_claim_id'])->first();
                if (!$warrantyClaim) {
                    throw new \Exception("Warranty claim with code_1C {$data['warranty_claim_code_id']} not found");
                }

                // Ищем ID по коду 1С в таблице defect_codes
                $defectCode = DefectCodes::where('code_1C', $data['defect_code'])->first();
                // if (!$defectCode) {
                //     throw new \Exception("Defect code with code_1C {$data['defect_code']} not found");
                // }

                // Ищем ID по коду 1С в таблице symptom_codes
                $symptomCode = SymptomCodes::where('code_1C', $data['symptom_code'])->first();
                // if (!$symptomCode) {
                //     throw new \Exception("Symptom code with code_1C {$data['symptom_code']} not found");
                // }

                $technicalConclusion = TechnicalConclusion::where('code_1C', $data['code_1C'])
                    ->orWhere(function ($query) use ($warrantyClaim) {
                        $query->where('warranty_claim_id', $warrantyClaim->id);
                    })
                    ->first();

                $technicalConclusion = TechnicalConclusion::updateOrCreate(
                    ['id' => $technicalConclusion ? $technicalConclusion->id : null],
                    [
                        'code_1C' => $data['code_1C'],
                        'warranty_claim_id' => $warrantyClaim->id,
                        'defect_code' => $defectCode ? $defectCode->id : null,
                        'symptom_code' => $symptomCode ? $symptomCode->id : null,
                        'conclusion' => $data['conclusion'],
                        'resolution' => $data['resolution'],
                        'date' => $data['date'],
                        'appeal_type' => $data['appeal_type'],
                        'number_1c' => $data['number_1c'],
                        'status_1c' => $data['status_1c'],
                    ],
                );
                
                if($technicalConclusion['status_1c'] == 1) {
                    $warrantyClaim->status = WarrantyClaimStatusEnum::approved->value;
                    $warrantyClaim->save();
                }

                if ($technicalConclusion->wasRecentlyCreated) {
                    $createdCount++;
                    $action = 'Создано'; 
                } else {
                    $updatedCount++;
                    $action = 'Оновлено';
                }
    
                Log::channel('technical_conclusions')->info("$action техническое заключение", ['id' => $technicalConclusion->id]);
    
            } catch (\Exception $e) {
                $errors[] = [
                    'error' => $e->getMessage(),
                    'data' => $data,
                ];
            }
        }
    
        return response()->json([
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
            'errors' => $errors,
        ], empty($errors) ? 200 : 400);
    }

}
