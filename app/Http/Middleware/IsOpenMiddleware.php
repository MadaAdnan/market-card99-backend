<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class IsOpenMiddleware
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
        $setting = Setting::first();
        if (!$setting->is_open) {
            if ((auth()->check() && (auth()->user()->email == 'mgd1alsham12345@gmail.com'||auth()->user()->email == 'admin@admin.com')) ) {
                return $next($request);
            }
            abort(501, $setting->msg_close);


        }
        return $next($request);
    }
}
