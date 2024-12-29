<?php

namespace App\Http\Middleware;

use App\Http\Resources\Api2\BillResource;
use App\Http\Resources\Api2\UserResource;
use App\Models\Bill;
use App\Support\HelperSupport;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RateLimit
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
        $id = $request->route()->parameter('bill');

        if (!$id) {
            return response()->json(['error' => 'ID is required'], 400);
        }

        // استخدام Cache لتحديد الحد
        $cacheKey = 'rate_limit_' . $id;

        if (Cache::has($cacheKey)) {
            $bill = Bill::find($id);
            return HelperSupport::sendData(['bill' => new BillResource($bill), 'user' => new UserResource(auth()->user())]);
        }

        // تعيين مدة التوقيت (30 ثانية)
        Cache::put($cacheKey, true, 15);

        return $next($request);
    }


}
