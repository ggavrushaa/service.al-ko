<?php

namespace App\Http\Controllers\TechnicalConclusion;

use Carbon\Carbon;
use App\Models\User;
use App\Models\DefectCodes;
use App\Models\UserPartner;
use App\Enums\ClaimTypeEnum;
use App\Models\SymptomCodes;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\WarrantyClaimApproved;
use App\Enums\WarrantyClaimStatusEnum;
use App\Http\Requests\Conclusion\ApproveTechnicalConclusionRequest;
use App\Models\TechnicalConclusion\TechnicalConclusion;
use App\Http\Requests\Conclusion\StoreTechnicalConclusionRequest;

class TechnicalConclusionController extends Controller
{
    public function index()
    {
        $conclusions = TechnicalConclusion::whereHas('warrantyClaim', function($query) {
            $query->whereIn('status', [
                WarrantyClaimStatusEnum::review->value, 
                WarrantyClaimStatusEnum::approved->value
            ]);
        })->orderBy('date', 'desc')->paginate(10);

        $authors = User::where('role_id', 2)->get();

        $warrantyClaims = [];

        foreach ($conclusions as $conclusion) {
            $warrantyClaim = WarrantyClaim::with('user')->find($conclusion->warranty_claim_id);
    
            if ($warrantyClaim) {
                $autor = User::find($warrantyClaim->autor);
                $warrantyClaims[$conclusion->id] = [
                    'claim' => $warrantyClaim,
                    'autor' => $autor,
                ];
            }
        }

        return view('app.conclusion.index', compact('conclusions', 'warrantyClaims', 'authors', ));
    }

    public function filter(Request $request)
    {
        $query = TechnicalConclusion::with('warrantyClaim.user', 'warrantyClaim.manager');

        if ($request->filled('date_start')) {
            try {
                $dateStart = Carbon::createFromFormat('m/d/Y', $request->date_start)->format('Y-m-d');
                $query->whereDate('created_at', '>=', $dateStart);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['date_start' => 'Неверный формат даты.']);
            }
        }

        if ($request->filled('date_end')) {
            try {
                $dateEnd = Carbon::createFromFormat('m/d/Y', $request->date_end)->format('Y-m-d');
                $query->whereDate('created_at', '<=', $dateEnd);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['date_end' => 'Неверный формат даты.']);
            }
        }

        if ($request->filled('article')) {
            $query->whereHas('warrantyClaim', function ($query) use ($request) {
                $query->where('product_article', 'like', '%' . $request->article . '%');
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('warrantyClaim', function ($query) use ($request) {
                $query->whereIn('status', $request->status);
            });
        }

        if ($request->filled('author') && $request->author != -1) {
            $query->whereHas('warrantyClaim.manager', function($q) use ($request) {
                $q->where('id', $request->author);
            });
        }

        $conclusions = $query->paginate(10);
        $authors = User::where('role_id', 2)->get();

        return view('app.conclusion.index', compact('conclusions', 'authors'));
    }


    public function create($id)
    {
        $warrantyClaim = WarrantyClaim::findOrFail($id);
        $autor = UserPartner::where('user_id', $warrantyClaim->autor)->first();
        $defectCodes = DefectCodes::all();
        $symptomCodes = SymptomCodes::all();
        $appealTypes = ClaimTypeEnum::cases();
        $managers = User::all();
        $conclusion = TechnicalConclusion::where('warranty_claim_id', $id)->first();
        
        $conclusion = TechnicalConclusion::where('warranty_claim_id', $id)->first();
    
        if (!$conclusion) {
            $conclusion = TechnicalConclusion::create([
                'warranty_claim_id' => $id,
                'defect_code' => null, 
                'symptom_code' => null, 
                'appeal_type' => null, 
                'conclusion' => null, 
                'resolution' => null, 
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return view('app.conclusion.edit', compact('warrantyClaim', 'defectCodes', 'symptomCodes', 'appealTypes', 'managers', 'autor', 'conclusion'));
    }

    public function store(StoreTechnicalConclusionRequest $request, $id)
    {
        $validatedData = $request->validated();
        $warrantyClaim = WarrantyClaim::findOrFail($id);

        $warrantyClaim->update($validatedData);

        return redirect()->route('warranty-claims.index')->with('status', 'Акт технічної експертизи створено');
    }

    public function update(ApproveTechnicalConclusionRequest $request, $id)
    {
        $validatedData = $request->validated();

        $technicalConclusion = TechnicalConclusion::where('warranty_claim_id', $id)->first();
        $technicalConclusion->update($validatedData);

        $action = $request->input('button');

        if ($action == 'approve') {
            $warrantyClaim = WarrantyClaim::findOrFail($id);
            $warrantyClaim->status = WarrantyClaimStatusEnum::approved->value;
            $warrantyClaim->save();
    
            event(new WarrantyClaimApproved($warrantyClaim));
            return redirect()->back()->with('status', 'Акт технічної експертизи оновлено та затверджено');
        } elseif ($action == 'save') {
            return redirect()->back()->with('status', 'Акт технічної експертизи оновлено');
        }  elseif ($action == 'save-exit') {
            return redirect()->route('app.conclusion.index')->with('status', 'Акт технічної експертизи збережено');
        }

    }

    public function sort(Request $request)
    {   
        $column = $request->input('column');
        $order = $request->input('order');
        
        if ($column === 'manager.first_name_ru') {
            $column = 'users.first_name_ru';
        }
        
        $conclusions = TechnicalConclusion::select('technical_conclusions.*')
            ->join('warranty_claims', 'technical_conclusions.warranty_claim_id', '=', 'warranty_claims.id')
            ->join('users', 'warranty_claims.manager_id', '=', 'users.id', 'left')
            ->with(['warrantyClaim' => function($query) {
                $query->with('manager', 'user');
            }])
            ->orderBy($column, $order)
            ->paginate(20);

        return response()->json($conclusions);
    }

    
}
    
