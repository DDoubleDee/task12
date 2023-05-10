<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class UserTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $bearer = $request->bearerToken();
        if (is_null($bearer)) {
            return response(['message' => 'Missing token'], 401)->header('Content-Type', 'application/json');
        }
        $bearer = User::where('accessToken', $bearer)->get()->first();
        if (is_null($bearer)) {
            return response(['message' => 'Invalid token'], 403)->header('Content-Type', 'application/json');
        } else {
            return $next($request);
        }
    }
}
