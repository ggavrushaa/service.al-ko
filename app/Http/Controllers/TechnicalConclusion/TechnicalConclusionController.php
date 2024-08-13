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
use App\Http\Controllers\Controller;
use App\Events\WarrantyClaimApproved;
use App\Enums\WarrantyClaimStatusEnum;
use App\Models\TechnicalConclusion\TechnicalConclusion;
use App\Http\Requests\Conclusion\StoreTechnicalConclusionRequest;
use Illuminate\Support\Facades\Log;

class TechnicalConclusionController extends Controller
{
    public function index()
    {
        $conclusions = TechnicalConclusion::paginate(20);
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
        

        return view('app.conclusion.edit', compact('warrantyClaim', 'defectCodes', 'symptomCodes', 'appealTypes', 'managers', 'autor', 'conclusion'));
    }

    public function store(StoreTechnicalConclusionRequest $request, $id)
    {
        $validatedData = $request->validated();
        $warrantyClaim = WarrantyClaim::findOrFail($id);

        $warrantyClaim->update($validatedData);

        return redirect()->route('warranty-claims.index')->with('status', 'Акт технічної експертизи створено');
    }

    public function update(StoreTechnicalConclusionRequest $request, $id)
    {
        $validatedData = $request->validated();

        $technicalConclusion = new TechnicalConclusion($validatedData);
        $technicalConclusion->warranty_claim_id = $id;
        $technicalConclusion->date = date('Y-m-d');
        $technicalConclusion->code_1C = rand(100000, 999999);
        $technicalConclusion->save();

        $warrantyClaim = WarrantyClaim::findOrFail($id);
        $warrantyClaim->status = WarrantyClaimStatusEnum::approved->value;
        $warrantyClaim->save();

        event(new WarrantyClaimApproved($warrantyClaim));

        return redirect()->back()->with('status', 'Акт технічної експертизи оновлено та затверджено');
    }

    public function save(StoreTechnicalConclusionRequest $request, $id)
    {
        $validatedData = $request->validated();

        $technicalConclusion = new TechnicalConclusion($validatedData);
        $technicalConclusion->warranty_claim_id = $id;
        $technicalConclusion->date = date('Y-m-d');
        $technicalConclusion->code_1C = rand(100000, 999999);
        $technicalConclusion->save();

        return redirect()->route('app.conclusion.index');
    }

    
}
    
