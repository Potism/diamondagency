<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class CheckStatusEmployer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('employer')->user()) {
            $user = auth()->guard('employer')->user();
            if ($user->status  && $user->ev  && $user->sv  && $user->tv) {
                return $next($request);
            } else {
                return redirect()->route('employer.authorization');
            }
        }
        abort(403);
    }
}
