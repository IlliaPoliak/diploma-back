<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class ApiAuthentication
{
    // const API_KEY_HEADER = 'x-api-key';

    public function handle(Request $request){
        // $token = $request->header(self::API_KEY_HEADER);

        // if ($token === null){
        //     return ['Unauthorized'];
        // }

        // if ($token !== config('services.api.token')){
        //     return ['Unauthorized'];
        // }

        // return $next($request);
    }
    
}
