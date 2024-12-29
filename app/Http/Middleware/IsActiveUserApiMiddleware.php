<?php

namespace App\Http\Middleware;

use App\Support\HelperSupport;
use Closure;
use Illuminate\Http\Request;

class IsActiveUserApiMiddleware
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
        if(auth()->user()->active){
            return $next($request);
        }
        HelperSupport::SendError(['msg'=>'تم حظر حسابك يرجى التواصل مع الإدارة']);
    }
}
