<?php

namespace App\Http\Controllers;

use App\Models\DefectCodes;
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

    public function getServiceWorks($group_id)
    {
        $works = ServiceWorks::where('product_group_id', $group_id)->get();
        return response()->json($works);
    }
}
