<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Проверяем, авторизован ли пользователь и совпадает ли его роль
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Доступ запрещен. У вас нет прав администратора.');
        }

        return $next($request);
    }
}
