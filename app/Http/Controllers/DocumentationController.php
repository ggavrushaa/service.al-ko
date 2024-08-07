<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use App\Models\WarrantyClaim;
use App\Models\Documentations\DocumentType;
use App\Models\Documentations\Documentation;
use App\Models\UserPartner;

class DocumentationController extends Controller
{
    public function index()
    {
        $documentations = Documentation::with(['documentType', 'productGroup'])->paginate(20);
        $documentTypes = DocumentType::all();
        $productGroups = ProductGroup::all();

        return view('app.documentations.index', compact('documentations', 'documentTypes', 'productGroups'));
    }

    public function fees()
    {
        $warrantyClaim = WarrantyClaim::all();
        return view('app.documentations.fees', compact('warrantyClaim', ));
    }
}
