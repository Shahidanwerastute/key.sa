<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Helpers\custom;
class Customer
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
        if(Auth::check() &&  custom::role() == "individual_customer" || custom::role() == "2"){
        // this is for access if true.
            return $next($request);
        }else{
            return redirect()->to('/')->with('error','You have no permission to perform this action');
        }
    }
}
