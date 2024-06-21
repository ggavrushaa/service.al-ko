<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ResolutionTemplate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ResolutionTemplateRequest;

class ResolutionTemplateController extends Controller
{
    public function store(ResolutionTemplateRequest $request)
    {
        $createdCount = 0;
        $updatedCount = 0;

        foreach ($request->all() as $item) {
            $resolutionTemplate = ResolutionTemplate::on('second_db')->where('code_1C', $item['code_1C'])->first();
            if ($resolutionTemplate) {
                $resolutionTemplate->update($item);
                $updatedCount++;
            } else {
                ResolutionTemplate::create($item);
                $createdCount++;
            }
        }

        $response = [
            'status' => 'success',
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
        ];

        Log::channel('resolution_templates')->info('Додано: ' . $createdCount . ', оновлено: ' . $updatedCount);

        return response()->json($response, 200);
    }
}
