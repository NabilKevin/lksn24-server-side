<?php

namespace App\Http\Middleware;

use App\Models\Administrator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Administrator::firstWhere('username', $request->user()->username);
        if($admin) {
            return $next($request);
        }
        return response()->json([
            "status" => "insufficient_permissions",
            "message" => "Access forbidden"
        ], 403);
    }
}
