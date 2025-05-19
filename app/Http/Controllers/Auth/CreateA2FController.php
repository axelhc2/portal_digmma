<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Session;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class CreateA2FController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        $userDataA2F = DB::table('users_a2f')->where('user_id', $user->id)->first();

        $google2fa = new Google2FA();

        if (!$userDataA2F) {
            $secretKey = $google2fa->generateSecretKey();

            DB::table('users_a2f')->insert([
                'user_id' => $user->id,
                'google_key' => $secretKey,
                'status' => 'valid',       
            ]);
        } else {
            $secretKey = $userDataA2F->google_key;
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'Digmma',
            $user->email,
            $secretKey
        );

        $fileName = "qr-{$user->id}.png";
        $qrPath = public_path("qrcodes/{$fileName}");

        $builder = new Builder(
            writer: new PngWriter(),
            data: $qrCodeUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        $result = $builder->build();
        $result->saveToFile($qrPath);

        return view('auth.two-factor.create', [
            'qrCodeUrl' => "/qrcodes/{$fileName}",
            'secretKey' => $secretKey,
        ]);
    }

    public function verify(Request $request)
    {
        $user = Auth::user();
        $userDataA2F = DB::table('users_a2f')->where('user_id', $user->id)->first();
        
        if (!$userDataA2F) {
            return redirect()->back()->withErrors(['error' => 'Configuration A2F non trouvée.']);
        }

        // Récupérer le code à 6 chiffres
        $code = $request->input('code_1') . 
                $request->input('code_2') . 
                $request->input('code_3') . 
                $request->input('code_4') . 
                $request->input('code_5') . 
                $request->input('code_6');

        $google2fa = new Google2FA();
        
        // Vérifier le code
        $valid = $google2fa->verifyKey($userDataA2F->google_key, $code);
        
        if ($valid) {
            // Mettre à jour le statut A2F de l'utilisateur
            DB::table('users_a2f')
                ->where('user_id', $user->id)
                ->update(['status' => 'active']);
            
            return redirect('two-factor');
        }
        
        return redirect()->back()->withErrors(['error' => 'Code invalide. Veuillez réessayer.']);
    }
}