<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoUsuarioMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $tipo)
    {
        $user = $request->user();

        abort_unless($user && (int) $user->tipo_usuario_id === (int) $tipo, 403);

        return $next($request);
    }
}
