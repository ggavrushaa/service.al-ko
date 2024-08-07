<?php

namespace App\Http\Controllers\WarrantyClaim;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WarrantyClaim;
use App\Http\Controllers\Controller;
use App\Models\WarrantyClaimComment;
use Illuminate\Support\Facades\Auth;

class WarrantyClaimCommentController extends Controller
{
    public function index($warrantyClaimId)
    {
        $comments = WarrantyClaimComment::where('warranty_claim_id', $warrantyClaimId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($comment) {
                $user = User::find($comment->user_id);
                $comment->user_name = $user ? $user->first_name_ru : 'Неизвестный';
                return $comment;
            });
        return response()->json($comments);
    }

    public function store(Request $request, $warrantyClaimId)
    {
        $comment = new WarrantyClaimComment();
        $comment->warranty_claim_id = $warrantyClaimId;
        $comment->comment = $request->comment;
        $comment->user_id = Auth::id();
        $comment->save();

        return response()->json($comment);
    }

    public function update(Request $request, $commentId)
    {
        $comment = WarrantyClaimComment::findOrFail($commentId);
        if ($comment->user_id == Auth::id()) {
            $comment->comment = $request->comment;
            $comment->save();

            return response()->json($comment);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function destroy($commentId)
    {
        $comment = WarrantyClaimComment::findOrFail($commentId);
        if ($comment->user_id == Auth::id()) {
            $comment->delete();

            return response()->json(['message' => 'Comment deleted']);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
