<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImportDocument;
use App\Models\UserPartner;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use App\Models\Documentations\DocumentType;
use App\Models\Documentations\Documentation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

    public function import(StoreImportDocument $request)
    {
        Log::info($request->all());

        if ($request->hasFile('file')) {
            Log::info('File detected');
            $file = $request->file('file');
            $filePath = $file->store('documents', 'public');
            Log::info('File stored at: ' . $filePath);
        } else {
            Log::info('No file detected');
            $filePath = null;
        }

        $document = new Documentation();
        $document->name = $request->name;
        $document->doc_type_id = $request->doc_type_id;
        $document->category_id = $request->category_id;
        $document->added = Carbon::now()->format('Y-m-d H:i:s');
        $document->file_path = $filePath;
        $document->save();

        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        $document = Documentation::findOrFail($id);

        $document->name = $request->input('doc-name');
        $document->doc_type_id = $request->input('doc_type_id');
        $document->category_id = $request->input('category_id');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('documents', 'public');
            $document->file_path = $filePath;
        }

        $document->save();

        return redirect()->back();
    }

    public function delete($id)
    {
        $document = Documentation::findOrFail($id);
        $document->delete();

        return redirect()->back();
    }
}
