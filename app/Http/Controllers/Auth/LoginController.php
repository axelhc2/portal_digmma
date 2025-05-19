<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->save();

            $user = Auth::user();
            Log::info('Utilisateur connecté', ['user_id' => $user->id, 'email' => $user->email]);
            
            $userDataA2F = DB::table('users_a2f')->where('user_id', $user->id)->first();
            
            if (!$userDataA2F) {
                Log::info('Redirection vers création A2F', ['user_id' => $user->id]);
                return redirect('/two-factor/create');
            } else {
                $sessionId = $request->session()->getId();
                DB::table('sessions')->where('id', $sessionId)->update(['status' => 'a2f']);
                Log::info('Redirection vers vérification A2F', ['user_id' => $user->id]);
                return redirect('/two-factor');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
} 