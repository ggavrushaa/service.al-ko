<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarrantyClaimFile;

class WarrantyClaimFileController extends Controller
{
    public function destroy($id)
    {
        $file = WarrantyClaimFile::find($id);

        if ($file) {

            if (file_exists(public_path($file->path))) {
                unlink(public_path($file->path));
            }

            $file->delete();

            return response()->json(['message' => 'File deleted successfully'], 200);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
    
}
