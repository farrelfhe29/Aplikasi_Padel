<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isApproved()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda belum disetujui oleh admin atau telah ditolak.');
        }

        return $next($request);
    }
}
