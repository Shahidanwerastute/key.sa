<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Custom;
use Auth;
class Admin
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
        if(Auth::check() && custom::role() == "admin"){
        // this is for access if true.
            return $next($request);
        }else{
            return redirect()->to('/admin')->with('error','You have no permission to perform this action');
        }
    }
    
}
