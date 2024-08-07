<?php

namespace App\Http\Controllers;

use App\Models\DefectCodes;
use App\Models\ProductGroup;
use App\Models\ServiceWorks;
use App\Models\SymptomCodes;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function defect()
    {
        $codes = DefectCodes::orderBy('name')->get();
        return view('guide.defect', compact('codes'));
    }

    public function symptom()
    {
        $codes = SymptomCodes::orderBy('name')->get();
        return view('guide.symptom', compact('codes'));
    }

    public function service()
    {
        $works = ServiceWorks::with('group')->orderBy('name')->get();
        return view('guide.service', compact('works'));
    }

  
    public function getServiceWorksByGroupId($groupId)
    {
        $productGroup = ProductGroup::find($groupId);

        if (!$productGroup) {
            return response()->json([], 404);
        }

        $serviceWorks = ServiceWorks::where('product_group_id', $productGroup->code_1C)->get();

        return response()->json($serviceWorks);
    }
}
