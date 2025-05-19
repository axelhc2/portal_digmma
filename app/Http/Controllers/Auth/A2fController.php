<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use PragmaRX\Google2FA\Google2FA;


class A2fController extends Controller
{


    public function view(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userId = Auth::id();

        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            abort(404, "Utilisateur introuvable.");
        }
        
        return view('auth.two-factor');
    }

    
}