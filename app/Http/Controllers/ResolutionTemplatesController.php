<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResolutionTemplatesController extends Controller
{
    public function getTemplates()
    {
        $templates = DB::connection('second_db')->table('resolution_templates')
        ->where('is_folder', 0)
        ->get();
        
        return response()->json($templates);
    }
}
