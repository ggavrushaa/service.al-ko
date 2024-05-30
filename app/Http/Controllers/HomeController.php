<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(User $user)
    {
        $user = Auth::user();
        
        if(!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Потрібна авторизація',
            ]);
        }

        return view('app.home.index', compact('user'));
    }
}
