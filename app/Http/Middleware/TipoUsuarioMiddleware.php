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
    public function handle(Request $request, Closure $next, ...$tipos)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $tipos = array_map('intval', $tipos);

        abort_unless(
            in_array((int) $user->tipo_usuario_id, $tipos, true),
            403
        );

        return $next($request);
    }
}
