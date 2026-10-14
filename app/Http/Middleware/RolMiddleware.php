<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $rolUsuario = $request->user()->rol->nombre;

        if (! in_array($rolUsuario, $roles)) {
            abort(403, 'No tienes permisos para entrar aqui.');
        }

        return $next($request);
    }
}
