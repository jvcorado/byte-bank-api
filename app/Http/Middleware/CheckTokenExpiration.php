<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();
        
        if ($token && $token->expires_at) {
            // Se o token expira em menos de 1 hora, renovar automaticamente
            $oneHourFromNow = now()->addHour();
            
            if ($token->expires_at->lt($oneHourFromNow)) {
                // Criar novo token
                $newToken = $request->user()->createToken('auth_token', ['*'], now()->addHours(24));
                
                // Adicionar header com novo token
                $response = $next($request);
                $response->headers->set('X-New-Token', $newToken->plainTextToken);
                $response->headers->set('X-Token-Expires-At', $newToken->accessToken->expires_at->toISOString());
                
                return $response;
            }
        }

        return $next($request);
    }
} 