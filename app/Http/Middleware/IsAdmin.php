<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = Auth::user()->roles;

        if($roles == 'PASIEN') {
            return redirect('pasien/dashboard');
        }elseif($roles == 'APOTEKER') {
            return redirect('apoteker/dashboard');
        }elseif($roles == 'DOKTER') {
            return redirect('dokter/dashboard');
        }
        return $next($request);
    }
}
