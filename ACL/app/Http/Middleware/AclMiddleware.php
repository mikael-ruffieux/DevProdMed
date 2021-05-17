<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Acl;

class AclMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // On récupère le rôle de l'utilisateur
        $role = auth()->user()->getAttributes()['role'];

        // On récupère le nom de la fonctionnalité que l'utilisateur essaye d'accéder
        // (après un peu de nettoyage; la fonctionnalité se trouve après le dernier \)
        // $request->route()->getActionName() : "App\Http\Controllers\UnControleur@index"
        // $fonctionnalite : "UnControleur@index"
        $fonctionnalite = substr(
            $request->route()->getActionName(),
            strrpos($request->route()->getActionName(), '\\') + 1
        );
        // On contrôle que le rôle de l'utilisateur a accès à cette fonctionnalité
        $aAccesA = (Acl::where('role', $role)->where(
            'fonctionnalite',
            $fonctionnalite
        )->count() != 0);

        // Si il n'a pas accès à cette fonctionnalité, on arrête le traitement
        if (!$aAccesA) {
            abort(403);
            //return response()->json(['message' => 'Forbidden'], 403);
        }

        // On passe la requête plus loin 
        // (soit au middleware suivant, soit au contrôleur)
        return $next($request);
    }
}
