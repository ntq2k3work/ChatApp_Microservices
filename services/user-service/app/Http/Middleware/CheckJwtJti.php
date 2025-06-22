<?php

namespace App\Http\Middleware;

use App\Models\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJwtJti
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $payload = auth()->payload();
        $jti = $payload->get('jti');
        $exists = JwtToken::where('jti', $jti)->exists();
        if (! $exists) {
            return response()->json(['message' => 'Token không hợp lệ hoặc đã bị thu hồi'], 401);
        }
        return $next($request);
    }
}
