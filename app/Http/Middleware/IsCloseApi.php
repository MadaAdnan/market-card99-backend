<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Support\HelperSupport;
use Auth;
use Closure;
use Illuminate\Http\Request;

class IsCloseApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken() && auth()->guard('sanctum')->check()) {
            try{
                Auth::setUser(
                    Auth::guard('sanctum')->user()
                );
            }catch (\Exception $e){

            }

        }


        return $next($request);
    }
}
