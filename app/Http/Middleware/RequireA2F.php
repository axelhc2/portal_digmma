<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RequireA2F
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $sessionId = $request->session()->getId();
        $sessionData = DB::table('sessions')->where('id', $sessionId)->first();

        if ($sessionData && $sessionData->status === 'a2f' && !$request->is('two-factor*')) {
            return redirect('/two-factor');
        }

        return $next($request);
    }
}