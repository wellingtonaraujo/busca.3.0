<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class AuthorizedAccessRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect('/login'); // Redireciona o usuário para a página de login, se não estiver autenticado
        } else if (Auth::check() && Hash::check('123456', Auth::user()->password)) {
            return redirect()->route('alterar-senha');
        }

        $name = $request->route(); // pega a rota solicitada
        $route = $name->getName(); //pega o nome da rota

        if (!Auth::user()->routeAccess($route)) {
            Alert::error('403: Acesso negado!', 'Você não possui permissão adequada para acessar esta funcionalidade do sistema');
            return back()->withInput();
        }

        return $next($request);
    }
}
