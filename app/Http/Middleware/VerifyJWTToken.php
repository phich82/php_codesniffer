<?php

namespace App\Http\Middleware;

use JWTAuth;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class VerifyJWTToken
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
        try{
            //$user = JWTAuth::toUser($request->input('token')); 
            if (!$user = JWTAuth::parseToken()->authenticate()) { // false: if user not found, otherwise, return user
                return response()->json(['user_not_found'], 404);
            }      
        } catch (JWTException $e) {
            if ($e instanceof TokenExpiredException){
                return response()->json(['token_expired' => $e->getStatusCode()]);
            } else if ($e instanceof TokenInvalidException) {
                return response()->json(['token_invalid' => $e->getStatusCode()]);
            } else {
                return response()->json(['token_absent' => 'Token is required']);
            }
        }
        return $next($request);
    }
}
