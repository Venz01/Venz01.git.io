<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceCatererDocumentUpdate
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'caterer') {
            return $next($request);
        }

        if (! $user->document_update_requested) {
            return $next($request);
        }

        $allowedRoutes = [
            'caterer.document-update.edit',
            'caterer.document-update.update',
            'logout',
        ];

        if (! $request->routeIs(...$allowedRoutes)) {
            return redirect()->route('caterer.document-update.edit');
        }

        return $next($request);
    }
}
