<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WarrantyClaimRequest;
use App\Models\ServiceWorks;

class WarrantyClaimController extends Controller
{
    public function store(WarrantyClaimRequest $request)
    {   
        Log::channel('warranty_claims')->info('Запрос на эндпоинт /warranty-claims', [
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'request' => $request->all(),
        ]);
    
        $createdCount = 0;
        $updatedCount = 0;
    
        $warrantyClaimsData = $request->all();
    
        foreach ($warrantyClaimsData as $data) {
            // Поиск или создание менеджера по имени
            $manager = User::where('first_name_ru', $data['manager_name'])->first();
            if (!$manager) {
                $manager = User::where('role_id', 3)->orderBy('id')->first(); // Админ с наименьшим id
            }
    
            // Логирование найденного менеджера
            Log::channel('warranty_claims')->info('Менеджер найден', [
                'manager' => $manager,
            ]);
    
            if ($manager) {
                $data['manager_id'] = $manager->id;
                unset($data['manager_name']);
            } else {
                Log::channel('warranty_claims')->error('Manager not found and default admin not found');
                return response()->json(['status' => 'error', 'message' => 'Manager not found'], 400);
            }
    
            Log::channel('warranty_claims')->info('Данные перед сохранением', [
                'data' => $data,
            ]);
    
            // Заполнение полей client_name и client_phone
            $data['client_name'] = $data['sender_name'];
            $data['client_phone'] = $data['sender_phone'];
    
            // Поиск или создание заявки
            $warrantyClaim = WarrantyClaim::where('code_1C', $data['code_1C'])->first();
            if ($warrantyClaim) {
                $warrantyClaim->update($data);
                $updatedCount++;
            } else {
                $warrantyClaim = WarrantyClaim::create($data);
                $createdCount++;
            }
    
            // Обработка связанных данных
            $warrantyClaim->spareParts()->delete();
            foreach ($data['spare_parts'] as $part) {
                $part['spare_parts'] = $part['spareparts_articul'];
                unset($part['spareparts_articul']);
                $warrantyClaim->spareParts()->create($part);
            }
    
            $warrantyClaim->files()->delete();
            foreach ($data['files'] as $file) {
                $warrantyClaim->files()->create($file);
            }
    
            $serviceWorksData = [];
            foreach ($data['service_works'] as $work) {
                $serviceWork = ServiceWorks::where('code_1c', $work['code_1c'])->first();
                if ($serviceWork) {
                    $work['service_work_id'] = $serviceWork->id;
                    $serviceWorksData[] = $work;
                }
            }
    
            $warrantyClaim->serviceWorks()->sync([]);
            foreach ($serviceWorksData as $work) {
                $warrantyClaim->serviceWorks()->attach($work['service_work_id'], [
                    'code_1C' => $work['code_1c'],
                    'line_number' => $work['line_number'],
                    'qty' => $work['qty'],
                    'price' => $work['price'],
                    'discount' => $work['discount'],
                    'sum' => $work['sum']
                ]);
            }
        }
    
        $response = [
            'status' => 'success',
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
        ];
    
        // Логирование ответа
        Log::channel('warranty_claims')->info('Ответ эндпоинта /warranty-claims', [
            'response' => $response,
        ]);
    
        return response()->json($response, 200);
    }

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');

        // Получение новых записей
        $newWarrantyClaims = WarrantyClaim::on('second_db')
            ->with(['spareParts', 'files', 'serviceWorks'])
            ->whereNull('code_1C')
            ->get()
            ->map(function ($claim) {
                $claim->manager_name = $claim->manager ? $claim->manager->first_name_ru : 'Сваток Александр';
                unset($claim->manager_id);
                unset($claim->client_name);
                unset($claim->client_phone);
                unset($claim->product_group_id);
                unset($claim['manager']);
                return $claim;
            });

        // Получение обновленных записей
        $updatedWarrantyClaims = WarrantyClaim::on('second_db')
            ->with(['spareParts', 'files', 'serviceWorks'])
            ->whereNotNull('code_1C')
            ->where('updated_at', '>', $dateFrom)
            ->get()
            ->map(function ($claim) {
                $claim->manager_name = $claim->manager ? $claim->manager->first_name_ru : 'Сваток Александр';
                unset($claim->manager_id);
                unset($claim->client_name);
                unset($claim->client_phone);
                unset($claim->product_group_id);
                unset($claim['manager']);
                return $claim;
            });

        Log::channel('warranty_claims')->info('Запрос на эндпоинт /warranty-claims - получение всех новых и обновленных заявок');

        return response()->json([
            'new_records_count' => $newWarrantyClaims->count(),
            'updated_records_count' => $updatedWarrantyClaims->count(),
            'new_data' => $newWarrantyClaims,
            'updated_data' => $updatedWarrantyClaims,
        ], 200);
    }
}
