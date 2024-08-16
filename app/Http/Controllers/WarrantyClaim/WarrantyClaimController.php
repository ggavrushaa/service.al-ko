<?php

namespace App\Http\Controllers\WarrantyClaim;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Contract;
use App\Models\Products;
use App\Models\UserPartner;
use App\Models\ProductGroup;
use App\Models\ServiceWorks;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use App\Models\GuaranteeCoupon;
use App\Models\UserServiceCentre;
use App\Models\WarrantyClaimFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Enums\WarrantyClaimStatusEnum;
use App\Models\WarrantyClaimSpareParts;
use App\Models\WarrantyClaimServiceWork;
use App\Http\Requests\WarrantyClaimRequest;
use App\Models\TechnicalConclusion\TechnicalConclusion;

class WarrantyClaimController extends Controller
{
    public function index()
    {
        $warrantyClaims = WarrantyClaim::with('user', 'manager')->orderBy('id', 'desc')->paginate(10);
        $authors = User::where('role_id', 2)->get();

        return view('app.warranty.index', compact('warrantyClaims', 'authors'));
    }

    public function filter(Request $request)
    {
        $query = WarrantyClaim::with('user', 'manager');

        if ($request->filled('date_start')) {
            try {
                $dateStart = Carbon::createFromFormat('m/d/Y', $request->date_start)->format('Y-m-d');
                $query->whereDate('created_at', '>=', $dateStart);
            } catch (\Exception $e) {
                $errors[] = 'Невірний формат початкової дати';
            }
        }
    
        if ($request->filled('date_end')) {
            try {
                $dateEnd = Carbon::createFromFormat('m/d/Y', $request->date_end)->format('Y-m-d');
                $query->whereDate('created_at', '<=', $dateEnd);
            } catch (\Exception $e) {
                $errors[] = 'Невірний формат кінцевої дати';
            }
        }

        if ($request->filled('article')) {
            $query->where('product_article', 'like', '%' . $request->article . '%');
        }

        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }

        if ($request->filled('author') && $request->author != -1) {
            $query->whereHas('manager', function($q) use ($request) {
                $q->where('id', $request->author);
            });
        }

        $warrantyClaims = $query->paginate(10);
        $authors = User::where('role_id', 2)->get();

        return view('app.warranty.index', compact('warrantyClaims', 'authors'));
    }

    public function edit($id)
    {
        $warrantyClaim = WarrantyClaim::with('user', 'manager', 'spareParts', 'serviceWorks')->find($id);

        $talon = GuaranteeCoupon::where('barcode', $warrantyClaim->barcode)
            ->where('factory_number', $warrantyClaim->factory_number)
            ->first();

        $product = Products::where('id', $talon->product_id ?? null)->first();

        $documentNumber = $warrantyClaim->number;
        $products = Products::paginate(10);
        $groups = ProductGroup::all();
        $works = ServiceWorks::all();
    
        $currentClaim = $warrantyClaim;
    
        $userId = auth()->id();
        $userServiceCentres = DB::connection('second_db')->table('users_services_centres')
            ->where('user_id', $userId)
            ->select('user_partner_id', 'default')
            ->get();

        $partnerIds = $userServiceCentres->pluck('user_partner_id');

        $serviceCenters = DB::connection('mysql')->table('user_partners')
            ->whereIn('id', $partnerIds)
            ->select('id', 'full_name_ru')
            ->get();

        $serviceCenters = $serviceCenters->map(function ($center) use ($userServiceCentres) {
            $center->default = $userServiceCentres->firstWhere('user_partner_id', $center->id)->default;
            return $center;
        });

        $defaultServicePartner = $currentClaim->service_partner ? $serviceCenters->firstWhere('id', $currentClaim->service_partner) 
         : $serviceCenters->firstWhere('default', 1);

        $defaultContract = null;
        $defaultDiscount = 0;
    
        if ($defaultServicePartner) {
            $defaultContract = Contract::where('partner_id', $defaultServicePartner->id)
                                       ->where('contract_type', 'Сервис')
                                       ->orderBy('added_time', 'desc')
                                       ->first();
        }
    
        if ($defaultContract) {
            $defaultDiscount = $defaultContract->discount;
        }

        $spareParts = $currentClaim->spareParts;

        foreach ($spareParts as $part) {
            $partDetails = Products::where('articul', $part->spare_parts)->first();
            if ($partDetails) {
                $part->name = $partDetails->name;
            } else {
                $part->name = 'Unknown';
            }
        }

        $serviceWorks = $currentClaim->serviceWorks;

        // цена сервисных работ
        $serviceWorksPrice = $defaultContract ? $defaultContract->service_works_price : 0;
        foreach ($serviceWorks as $work) {
            $work->price = $serviceWorksPrice;
            $work->total_price = $work->duration_decimal * $serviceWorksPrice;
        }
    
        return view('app.warranty.edit', compact('talon', 'groups', 'works', 'documentNumber', 'product', 'products', 'serviceCenters', 'currentClaim', 'defaultServicePartner', 'defaultDiscount', 'defaultContract', 'spareParts', 'serviceWorks'));
    }

    public function create($barcode, $factory_number = null)
    {
        $warrantyClaim = WarrantyClaim::with('user', 'manager', 'spareParts', 'serviceWorks')
            ->where('barcode', $barcode)
            ->orWhere('factory_number', $factory_number)
            ->first();

        $talon = GuaranteeCoupon::where('status', 'ACTIVE')
            ->where('barcode', $barcode)
            ->where('factory_number', $factory_number)
            ->firstOrFail();
            
        $product = Products::where('id', $talon->product_id ?? null)->first();

        if (!$warrantyClaim) {
        // Создаем новую заявку
        $maxDocumentNumber = DB::connection('second_db')->table('warranty_claims')->max('number');
        $maxDocumentNumber = $maxDocumentNumber ? intval($maxDocumentNumber) : 0;
        $documentNumber = $maxDocumentNumber + 1;

        $warrantyClaim = new WarrantyClaim([
            'barcode' => $talon->barcode,
            'factory_number' => $talon->factory_number,
            'client_name' => $talon->customer,
            'product_name' => $product->name,
            'product_article' => $product->articul,
            'number' => $documentNumber,
            'date' => now()->format('Y-m-d H:i:s'),
            'date_of_sale' => $talon->date,
            'date_of_claim' => now()->format('Y-m-d'),
            'status' => WarrantyClaimStatusEnum::new->value,
            'client_phone' => $talon->phone,
            'point_of_sale' => $talon->partner_name,
        ]);
            // dd($warrantyClaim);
            // $warrantyClaim->save();
        
        } else {
            $documentNumber = $warrantyClaim->number;
        }

        $products = Products::paginate(10);
        $groups = ProductGroup::all();
        $works = ServiceWorks::all();
    
         $currentClaim = $warrantyClaim;
    
        $userId = auth()->id();
        $userServiceCentres = DB::connection('second_db')->table('users_services_centres')
            ->where('user_id', $userId)
            ->select('user_partner_id', 'default')
            ->get();

        $partnerIds = $userServiceCentres->pluck('user_partner_id');

        $serviceCenters = DB::connection('mysql')->table('user_partners')
            ->whereIn('id', $partnerIds)
            ->select('id', 'full_name_ru')
            ->get();

        $serviceCenters = $serviceCenters->map(function ($center) use ($userServiceCentres) {
            $center->default = $userServiceCentres->firstWhere('user_partner_id', $center->id)->default;
            return $center;
        });

        $defaultServicePartner = $currentClaim->service_partner ? $serviceCenters->firstWhere('id', $currentClaim->service_partner) 
         : $serviceCenters->firstWhere('default', 1);

        $defaultContract = null;
        $defaultDiscount = 0;
    
        if ($defaultServicePartner) {
            $defaultContract = Contract::where('partner_id', $defaultServicePartner->id)
                                       ->where('contract_type', 'Сервис')
                                       ->orderBy('added_time', 'desc')
                                       ->first();
        }
    
        if ($defaultContract) {
            $defaultDiscount = $defaultContract->discount;
        }

        $spareParts = $currentClaim->spareParts;

        foreach ($spareParts as $part) {
            $partDetails = Products::where('articul', $part->spare_parts)->first();
            if ($partDetails) {
                $part->name = $partDetails->name;
            } else {
                $part->name = 'Unknown';
            }
        }

        $serviceWorks = $currentClaim->serviceWorks;

        // цена сервисных работ
        $serviceWorksPrice = $defaultContract ? $defaultContract->service_works_price : 0;
        foreach ($serviceWorks as $work) {
            $work->price = $serviceWorksPrice;
            $work->total_price = $work->duration_decimal * $serviceWorksPrice;
        }
    
        return view('app.warranty.create', compact('talon', 'groups', 'works', 'documentNumber', 'product', 'products', 'serviceCenters', 'currentClaim', 'defaultServicePartner', 'defaultDiscount', 'defaultContract', 'spareParts', 'serviceWorks', ));
    }

    public function getContractDetails(Request $request)
    {
        $serviceCenterId = $request->input('service_center_id');
        $contract = Contract::where('partner_id', $serviceCenterId)
                            ->where('contract_type', 'Сервис')
                            ->orderBy('added_time', 'desc')
                            ->get();

        if ($contract) {
            return response()->json($contract->toArray());
        }

        return response()->json([
            'message' => 'Contract not found',
        ], 404);
    }
    
    public function save(WarrantyClaimRequest $request)
    {
        Log::info('Request:', $request->all());
        $data = $request->validated();

        Log::info('Validated Data:', $data);

        try {
            if (isset($data['date_of_claim'])) {
                $data['date_of_claim'] = Carbon::parse($data['date_of_claim'])->format('Y-m-d');
            }
    
            if (isset($data['date_of_sale'])) {
                $data['date_of_sale'] = Carbon::parse($data['date_of_sale'])->format('Y-m-d');
            }
        } catch (\Exception $e) {
            Log::error('Date Format Error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Неверный формат даты.');
        }

        try {
            $warrantyClaim = WarrantyClaim::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('warranty_claims_files');
                        WarrantyClaimFile::create([
                            'warranty_claim_id' => $warrantyClaim->id,
                            'path' => $path,
                            'filename' => $file->getClientOriginalName(),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }

     // Получение контракта для расчета цен и скидок
     $contract = Contract::find($data['service_contract']);
     $serviceWorksPrice = $contract ? $contract->service_works_price : 0;
     $discount = $contract ? $contract->discount : 0;

    // Обработка и сохранение сервисных работ
    if (!empty($data['service_works'])) {
        foreach ($data['service_works'] as $index => $serviceWorkId) {
            $qty =  isset($data['hours'][$index]) ? (float) $data['hours'][$index] : 0.5;
            $price = $serviceWorksPrice;
            $sum = $price * $qty;
            $lineNumber = $index + 1;
            WarrantyClaimServiceWork::updateOrCreate(
                [
                    'warranty_claim_id' => $warrantyClaim->id,
                    'service_work_id' => $serviceWorkId,
                ],
                [
                    'line_number' => $lineNumber,
                    'qty' => $qty,
                    'discount' => $discount,
                    'price' => $price,
                    'sum' => $sum,
                ]
            );
        }

        Log::info('Service works saved:', $data['service_works']);
    }


    // Обработка и сохранение запчастей
    if (!empty($data['spare_parts'])) {
        // Получаем discount из контракта
        $contract = Contract::find($warrantyClaim->service_contract);
        $discount = $contract ? $contract->discount : 0;
    
        // Фильтруем и обрабатываем запчасти
        $filteredSpareParts = array_filter($data['spare_parts'], function($part) {
            return !is_null($part['spare_parts']) && !is_null($part['name']) && !is_null($part['qty']) && !is_null($part['price']) && !is_null($part['sum']);
        });
    
        Log::info('Filtered spare parts:', $filteredSpareParts);
    
        $lineNumber = 1; // Начинаем с первой строки
    
        foreach ($filteredSpareParts as $part) {
            WarrantyClaimSpareParts::updateOrCreate(
                [
                    'warranty_claim_id' => $warrantyClaim->id,
                    'spare_parts' => $part['spare_parts']
                ],
                [
                    'line_number' => $lineNumber,
                    'qty' => $part['qty'],
                    'price' => $part['price'],
                    'discount' => $discount,
                    'sum' => $part['sum']
                ]
            );
    
            $lineNumber++; // Увеличиваем line_number для следующей запчасти
        }
    
        Log::info('Spare parts saved:', $filteredSpareParts);
    }

            return redirect()->route('app.warranty.edit', $warrantyClaim->id)->with('status', 'Збережено');
        } catch (\Exception $e) {
            Log::error('Save Error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Помилка збереження');
        }
    }
    

    public function delete($id)
    {
        $warrantyClaim = WarrantyClaim::findOrFail($id);
        $warrantyClaim->delete();

        return redirect()->route('warranty-claims.index')->with('status', 'Заявка успішно видалена');
    }

    public function getContracts($centerId)
    {
        $contracts = Contract::where('partner_id', $centerId)
                             ->where('contract_type', 'Серивс')
                             ->orderBy('added_time', 'desc')
                             ->get();

                             Log::info('Contracts found:', ['contracts' => $contracts]);

        return response()->json($contracts);
    }

    public function sendToReview(Request $request, $id)
    {
        Log::info('Request:', $request->all());
        $data = $request->validate([
            'barcode' => 'nullable',
            'factory_number' => 'nullable',
            'client_name' => 'required',
            'product_name' => 'required',
            'product_article' => 'required',
            'number' => 'required',
            'date' => 'required|date',
            'date_of_sale' => 'required|date',
            'date_of_claim' => 'required|date',
            'status' => 'required',
            'service_partner' => 'nullable',
            'service_contract' => 'nullable',
            'client_phone' => 'nullable',
            'sender_name' => 'nullable',
            'sender_phone' => 'nullable',
            'details' => 'nullable',
            'deteails_reason' => 'nullable',
            'product_group_id' => 'nullable',
            'service_works' => 'nullable|array',
            'service_works.*' => 'string',
            'spare_parts' => 'nullable|array',
            'spare_parts.*.spare_parts' => 'nullable|string|max:255',
            'spare_parts.*.name' => 'nullable|string|max:255',
            'spare_parts.*.qty' => 'nullable|integer',
            'spare_parts.*.price' => 'nullable|numeric',
            'spare_parts.*.sum' => 'nullable|numeric',
        ]);

        $data['status'] = WarrantyClaimStatusEnum::sent;

        try {
            $warrantyClaim = WarrantyClaim::find($id);
            if (!$warrantyClaim) {
                $data['id'] = $id;
                $warrantyClaim = WarrantyClaim::create($data);
            } else {
                $warrantyClaim->update($data);
            }

            Log::info('Warranty Claim:', $warrantyClaim->toArray());

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    if ($file->isValid()) {
                        $filename = $file->getClientOriginalName();
                        $path = $file->store('warranty_claims_files');

                        WarrantyClaimFile::create([
                            'warranty_claim_id' => $warrantyClaim->id,
                            'path' => $path,
                            'filename' => $filename,
                        ]);
                    }
                }
            }

    // Обработка и сохранение сервисных работ
    if (!empty($data['service_works'])) {
        foreach ($data['service_works'] as $serviceWorkId) {
            WarrantyClaimServiceWork::create([
                'warranty_claim_id' => $warrantyClaim->id,
                'service_work_id' => $serviceWorkId,
            ]);
        }

        Log::info('Service works saved:', $data['service_works']);
    }

    // Обработка и сохранение запчастей
    if (!empty($data['spare_parts'])) {
        $filteredSpareParts = array_filter($data['spare_parts'], function($part) {
            return !is_null($part['spare_parts']) && !is_null($part['name']) && !is_null($part['qty']) && !is_null($part['price']) && !is_null($part['sum']);
        });

        Log::info('Filtered spare parts:', $filteredSpareParts);

        WarrantyClaimSpareParts::where('warranty_claim_id', $warrantyClaim->id)->delete();
        foreach ($filteredSpareParts as $part) {
            WarrantyClaimSpareParts::create([
                'warranty_claim_id' => $warrantyClaim->id,
                'spare_parts' => $part['spare_parts'], 
                'qty' => $part['qty'],
                'price' => $part['price'],
                'sum' => $part['sum'],
            ]);
        }

            Log::info('Spare parts saved:', $filteredSpareParts);
        }

            return redirect()->route('app.warranty.edit', $warrantyClaim->id)->with('status', 'Відправлено на розгляд');
        } catch (\Exception $e) {
            Log::error('Save Error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Помилка збереження');
        }
    }


    public function takeToWork($id)
    {
        $warrantyClaim = WarrantyClaim::findOrFail($id);
        $warrantyClaim->status = WarrantyClaimStatusEnum::review;
        if($warrantyClaim->manager_id == null){
            $warrantyClaim->manager_id = Auth::user()->id;
        }
        $warrantyClaim->save();

        return redirect()->back()->with('status', 'Розглядається');
    }

    public function sort(Request $request)
    {
        $column = $request->input('column');
        $order = $request->input('order');
    
        $warrantyClaims = WarrantyClaim::with('user', 'manager', 'spareParts', 'serviceWorks')
            ->when($column === 'amount_vat', function ($query) use ($order) {
                $query->selectRaw('warranty_claims.*, (
                    SELECT SUM(amount_vat) FROM warranty_claim_spareparts 
                    WHERE warranty_claim_spareparts.warranty_claim_id = warranty_claims.id
                ) as total_amount_vat')
                ->orderBy('total_amount_vat', $order);
            })
            ->when($column !== 'amount_vat', function ($query) use ($column, $order) {
                $query->orderBy($column, $order);
            })
            ->paginate(10);

        Log::info('Sorted data:', ['data' => $warrantyClaims]); 
    
        return response()->json($warrantyClaims);
    }
    

    public function updateManager($claim, Request $request)
    {
        DB::enableQueryLog();

        Log::info('Updating manager', ['claimId' => $claim, 'managerId' => $request->input('manager_id')]);
    
        $managerId = $request->input('manager_id');
        $claim = WarrantyClaim::find($claim);
        
        if ($claim) {
            Log::info('Claim found', ['claim' => $claim]);
            $claim->update(['manager_id' => $managerId]);
            $claim->save();
            
            Log::info('SQL Query Log:', DB::getQueryLog());
    
            return response()->json(['success' => true, 'message' => 'Менеджера успішно змінено']);
        }
    
        Log::error('Claim not found', ['claimID' => $claim]);
        return response()->json(['success' => false, 'message' => 'Заява не знайдена'], 404);
    }

    public function destroyImage($id)
    {
        $file = WarrantyClaimFile::find($id);
        if($file) {
            $file->delete();
            return response()->json(['success' => true, ]);
        } else {
            return response()->json(['success' => false]);
        }
    }


}
