<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FA\Google2FA;

class A2FController extends Controller
{
    public function show()
    {
        return view('auth.two-factor');
    }

    public function verify(Request $request)
    {
        $user = Auth::user();
        $userDataA2F = DB::table('users_a2f')->where('user_id', $user->id)->first();
        
        if (!$userDataA2F) {
            return redirect()->back()->withErrors(['error' => 'Configuration A2F non trouvée.']);
        }

        $code = $request->input('code_1') . 
                $request->input('code_2') . 
                $request->input('code_3') . 
                $request->input('code_4') . 
                $request->input('code_5') . 
                $request->input('code_6');

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($userDataA2F->google_key, $code);
        
        if ($valid) {
            $sessionId = $request->session()->getId();
            DB::table('sessions')->where('id', $sessionId)->update(['status' => 'active']);
            return redirect('/');
        }
        
        return redirect()->back()->withErrors(['error' => 'Code invalide. Veuillez réessayer.']);
    }
} 