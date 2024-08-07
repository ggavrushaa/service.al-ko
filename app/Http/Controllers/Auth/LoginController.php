<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    public function index()
    {
        return view('app.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->only('email', 'password');
        $remember = (bool) $request->input('remember');

        $user = User::where('email', $data['email'])->first();
        
          if (!$user || $user->password !== $data['password']) {
            return back()->withErrors([
                'email' => 'Не верный логин или пароль',
            ])->onlyInput('email');
        }

        Auth::login($user, $remember);
        
        $request->session()->regenerate();
        
        return to_route('app.home.index');
    }
}
