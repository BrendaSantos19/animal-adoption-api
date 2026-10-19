<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() instanceof \App\Models\Staff) {
            return response()->json(['message' => 'Acesso permitido apenas ao staff.'], 403);
        }

        return $next($request);
    }
}
