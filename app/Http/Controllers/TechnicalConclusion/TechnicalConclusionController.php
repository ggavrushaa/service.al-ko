<?php

namespace App\Http\Controllers\TechnicalConclusion;

use Exception;
use Carbon\Carbon;
use App\Models\Contract;
use App\Models\UserPartner;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Enums\TechnicalConclusionStatusEnum;
use App\Models\TechnicalConclusion\TechnicalConclusion;
use App\Http\Requests\Conclusion\CreateConclusionRequest;
use App\Models\TechnicalConclusion\TechnicalConclusionSparePart;

class TechnicalConclusionController extends Controller
{
    public function index()
    {
        $conclusions = TechnicalConclusion::with('user')->get();
        return view('app.conclusion.index', compact('conclusions'));
    }

    public function store(CreateConclusionRequest $request)
    {
        $data = $request->validated();
        Log::info('Валидация успешна', ['data' => $data]);
        
        try {
            $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->format('Y-m-d H:i:s');
            $data['date_of_sale'] = Carbon::createFromFormat('d.m.Y', $data['date_of_sale'])->format('Y-m-d H:i:s');
            $data['date_of_claim'] = Carbon::createFromFormat('d/m/Y', $data['date_of_claim'])->format('Y-m-d H:i:s');
            Log::info('Преобразование дат успешно', ['data' => $data]);
        } catch (Exception $e) {
            Log::error('Ошибка преобразования даты: ' . $e->getMessage(), [
                'date' => $data['date'],
                'date_of_sale' => $data['date_of_sale'],
                'date_of_claim' => $data['date_of_claim'],
            ]);
            return back()->withErrors('Ошибка преобразования даты.');
        }
    
        $filePaths = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $path = $file->store('technical_conclusions', 'public');
                $filePaths[] = $path;
            }
        }
        Log::info('Файлы успешно загружены', ['filePaths' => $filePaths]);
    
        $user = auth()->user();
        $partner = UserPartner::where('user_id', $user->id)->first();
        if (!$partner) {
            Log::error('Партнёр не найден для текущего пользователя', ['user_id' => $user->id]);
            return back()->withErrors('Партнёр не найден для текущего пользователя.');
        }
    
        $contract = Contract::where('partner_id', $partner->main_partner_id)
            ->where('order_type_id', 3)
            ->orderBy('id', 'desc')
            ->first();
    
        if (!$contract) {
            Log::error('Актуальный договор не найден для текущего партнёра', ['partner_id' => $partner->id]);
            return back()->withErrors('Актуальный договор не найден для текущего партнёра.');
        }
    
        try {
            $conclusion = new TechnicalConclusion();
            $conclusion->fill($data);
            $conclusion->file_paths = json_encode($filePaths);
            $conclusion->service_partner = $partner->id; 
            $conclusion->service_contract = $contract->id;
            $conclusion->type_of_claim = $data['type_of_claim'] ?? 'Гарантійний ремонт';
            $conclusion->resolution = $data['resolution'] ?? 'Example resolution';
            $conclusion->status = TechnicalConclusionStatusEnum::review;
    
            Log::info('Данные перед сохранением акта технической экспертизы', ['conclusion' => $conclusion->toArray()]);
    
            $conclusion->save();
            Log::info('Акт технической экспертизы успешно сохранен', ['conclusion_id' => $conclusion->id]);
    
            if (isset($data['spare_parts'])) {
                foreach ($data['spare_parts'] as $sparePart) {
                    $technicalConclusionSparePart = new TechnicalConclusionSparePart([
                        'technical_conclusion_id' => $conclusion->id,
                        'spare_part_id' => $sparePart['id'],
                    ]);
                    $technicalConclusionSparePart->save();
                }
                Log::info('Использованные запчасти успешно сохранены', ['conclusion_id' => $conclusion->id]);
            }
    
            $claim = WarrantyClaim::where('code_1C', $data['code_1C'])->first();
            $technicalConclusion = TechnicalConclusion::where('parent_doc', $claim->id)->first();

            return redirect()->back()
                ->with('status', $technicalConclusion->status->value)
                ->with('claim', $claim)
                ->with('conclusion', $technicalConclusion);

                
            } catch (Exception $e) {
                Log::error('Ошибка сохранения акта технической экспертизы: ' . $e->getMessage(), [
                    'data' => $data,
                    'files' => $filePaths,
                ]);
                return back()->withErrors('Ошибка сохранения акта технической экспертизы.');
            }
    }    

}
    
