<?php

namespace App\Http\Controllers\TechnicalConclusion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GuaranteeCoupon;
use App\Models\ResolutionTemplate;
use App\Models\TechnicalConclusion\TechnicalConclusion;
use Illuminate\Support\Facades\View;

class PDFController extends Controller
{
    public function generatePDF($id)
    {   
        $conclusion = TechnicalConclusion::with('warrantyClaim')->findOrFail($id);
        $talon = GuaranteeCoupon::where('factory_number', $conclusion->warrantyClaim->factory_number)->first();

        $data = [
            'conclusion' => $conclusion,
            'warrantyClaim' => $conclusion->warrantyClaim,
            'talon' => $talon
        ];


        $html = View::make('pdf.conclusion', $data)->render();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);

        $mpdf->Output('technical_conclusion_' . $id . '.pdf', 'D');
    }
}
