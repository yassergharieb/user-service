<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthenticate  extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard): Response
    {
        if ($guard != null) {
            auth()->shouldUse($guard);
            $token  =  $request->header('api-token');
            $request->headers->set('api-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer ' . $token, true);

            try {

                $user =  JWTAuth::parseToken()->authenticate();
            } catch (TokenExpiredException $ex) {

                return response()->json(['msg' =>  $ex->getMessage(), 'code' => $ex->getCode()]);
            } catch (JWTException $ex) {
                return response()->json(['msg' =>  $ex->getMessage(), 'code' => $ex->getCode()]);
            }
        }

        return $next($request);
    }
}
