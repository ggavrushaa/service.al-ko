<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ServiceWorks;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Enums\WarrantyClaimStatusEnum;
use App\Models\WarrantyClaimServiceWork;
use App\Http\Requests\Api\WarrantyClaimRequest;

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
        $errors = [];
    
        $warrantyClaimsData = $request->all();
    
        foreach ($warrantyClaimsData as $data) {
            try {
                // Поиск или создание менеджера по имени
                $manager = User::where('first_name_ru', $data['manager_name'])->first();
                if (!$manager) {
                    $manager = User::where('role_id', 3)->orderBy('id')->first(); // Админ с наименьшим id
                }
    
                if (!$manager) {
                    throw new \Exception('Manager not found');
                }
    
                $data['manager_id'] = $manager->id;
                unset($data['manager_name']);
    
                // Заполнение полей client_name и client_phone
                $data['client_name'] = $data['sender_name'];
                $data['client_phone'] = $data['sender_phone'];
    
                if (isset($data['id'])) {
                    $warrantyClaim = WarrantyClaim::find($data['id']);
                    if (!$warrantyClaim) {
                        throw new \Exception('Warranty claim not found for the provided ID');
                    }
                } else {
                    $warrantyClaim = null;
                }

                if ($warrantyClaim) {
                    $warrantyClaim->update($data);
                    $updatedCount++;
                } else {
                    $user = User::where('first_name_ru', $data['autor_name'])->first();
                    if ($user) {
                        $data['autor'] = $user->id;
                    } else {
                        throw new \Exception('Author not found');
                    }
                    $data['autor'] = $user->id;
                    unset($data['autor_name']);
                    
                    $warrantyClaim = WarrantyClaim::create($data);
                    $createdCount++;
                }
    
                $sparePartsSum = 0;
                $warrantyClaim->spareParts()->delete();
                foreach ($data['spare_parts'] as $part) {
                    $part['spare_parts'] = $part['spareparts_articul'];
                    unset($part['spareparts_articul']);
                    $part['sum'] = ($part['price'] - ($part['price'] * $part['discount'] / 100)) * $part['qty'];
                    $sparePartsSum += $part['sum'];
                    $warrantyClaim->spareParts()->create($part);
                }


                $serviceWorksSum = 0;
                $warrantyClaim->files()->delete();
                foreach ($data['files'] as $file) {
                    $warrantyClaim->files()->create($file);
                }
    
                WarrantyClaimServiceWork::where('warranty_claim_id', $warrantyClaim->id)->delete();
                foreach ($data['service_works'] as $work) {
                    $serviceWork = ServiceWorks::where('code_1C', $work['code_1c'])->first();
                    if ($serviceWork) {
                        $work['service_work_id'] = $serviceWork->id;
                        unset($work['code_1c']);
                        $work['sum'] = ($work['price'] - ($work['price'] * $work['discount'] / 100)) * $work['qty'];
                        $serviceWorksSum += $work['sum'];
                        $work['warranty_claim_id'] = $warrantyClaim->id;
                        WarrantyClaimServiceWork::create($work);
                    } else {
                        throw new \Exception("Service work with code_1C {$work['code_1c']} not found");
                    }
                }

                $warrantyClaim->service_works_sum = $serviceWorksSum;
                $warrantyClaim->spare_parts_sum = $sparePartsSum;
                $warrantyClaim->save();

            } catch (\Exception $e) {
                // Добавляем ошибку в массив ошибок
                $errors[] = [
                    'error' => $e->getMessage(),
                    'data' => $data,
                ];
            }
        }
    
        // Формируем окончательный ответ
        $response = [
            'status' => empty($errors) ? 'success' : 'partial_success',
            'created_count' => $createdCount,
            'updated_count' => $updatedCount,
            'errors' => $errors,
        ];
    
        // Логирование ответа
        Log::channel('warranty_claims')->info('Ответ эндпоинта /warranty-claims', [
            'response' => $response,
        ]);
    
        return response()->json($response, empty($errors) ? 200 : 400)
                     ->withHeaders([
                        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                        'Pragma' => 'no-cache',
                     ]);
    }

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $formattedDateFrom = "'$dateFrom'";

        // Получение новых записей
        $newWarrantyClaims = WarrantyClaim::on('second_db')
            ->with(['spareParts', 'files', 'serviceWorksAPI'])
            ->whereNull('code_1C')
            ->where('updated_at', '>', DB::raw($formattedDateFrom))
            ->where('status', '!=', WarrantyClaimStatusEnum::new)
            ->get();

        $newWarrantyClaims = $newWarrantyClaims->map(function ($claim) {
                $claim->created = date('Y-m-d H:i:s', strtotime($claim->created_at));
                $claim->updated = date('Y-m-d H:i:s', strtotime($claim->updated_at));
                unset($claim->created_at);
                unset($claim->updated_at);

                $claim->manager_name = $claim->manager ? $claim->manager->first_name_ru : 'Сваток Александр';
                unset($claim->manager_id);
                unset($claim->client_name);
                unset($claim->client_phone);
                unset($claim->product_group_id);
                unset($claim->type_of_claim);
                unset($claim['manager']);


                $claim->spare_parts = $claim->spareParts->map(function ($part) {
                    return [
                        'spareparts_articul' => $part['spare_parts'],
                        'line_number' => $part['line_number'],
                        'qty' => $part['qty'],
                        'price' => $part['price'],
                        'discount' => $part['discount'],
                    ];
                });
                unset($claim['spareParts']);

                $claim->service_work = $claim->serviceWorksAPI->map(function ($work) {
                    $serviceWork = $work->serviceWork; 
                
                    return [
                        'code_1C' => $serviceWork ? $serviceWork->code_1C : null,
                        'line_number' => $work->line_number,
                        'qty' => $work->qty,
                        'price' => $work->price,
                        'discount' => $work->discount,
                    ];
                });
                unset($claim['serviceWorksAPI']);

                $claim->files = $claim->files->map(function ($file) {
                    return [
                        'path' => $file->path,
                        'filename' => $file->filename,
                        'created_at' => Carbon::parse($file->created_at)->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::parse($file->updated_at)->format('Y-m-d H:i:s'),
                    ];
                });

                return $claim;
            });

        // Получение обновленных записей
        $updatedWarrantyClaims = WarrantyClaim::on('second_db')
            ->with(['spareParts', 'files', 'serviceWorksAPI'])  
            ->whereNotNull('code_1C')
            ->where('updated_at', '>', DB::raw($formattedDateFrom))
            ->where('status', '!=', WarrantyClaimStatusEnum::new)
            ->get();
            $updatedWarrantyClaims = $updatedWarrantyClaims->map(function ($claim) {
                $claim->created = date('Y-m-d H:i:s', strtotime($claim->created_at));
                $claim->updated = date('Y-m-d H:i:s', strtotime($claim->updated_at));
                unset($claim->created_at);
                unset($claim->updated_at);

                $claim->manager_name = $claim->manager ? $claim->manager->first_name_ru : 'Сваток Александр';
                unset($claim->manager_id);
                unset($claim->client_name);
                unset($claim->client_phone);
                unset($claim->product_group_id);
                unset($claim->type_of_claim);
                unset($claim['manager']);

                $claim->spare_parts = $claim->spareParts->map(function ($part) {
                    return [
                        'spareparts_articul' => $part['spare_parts'],
                        'line_number' => $part['line_number'],
                        'qty' => $part['qty'],
                        'price' => $part['price'],
                        'discount' => $part['discount'],
                    ];
                });
                unset($claim['spareParts']);

                $claim->service_work = $claim->serviceWorksAPI->map(function ($work) {
                    $serviceWork = $work->serviceWork; 
                
                    return [
                        'code_1C' => $serviceWork ? $serviceWork->code_1C : null,
                        'line_number' => $work->line_number,
                        'qty' => $work->qty,
                        'price' => $work->price,
                        'discount' => $work->discount,
                    ];
                });
                unset($claim['serviceWorksAPI']);

                $claim->files = $claim->files->map(function ($file) {
                    return [
                        'path' => $file->path,
                        'filename' => $file->filename,
                        'created_at' => Carbon::parse($file->created_at)->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::parse($file->updated_at)->format('Y-m-d H:i:s'),
                    ];
                });

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
