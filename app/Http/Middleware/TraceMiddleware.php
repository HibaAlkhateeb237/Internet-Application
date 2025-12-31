<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TraceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $actor = auth('admin')->user() ?? auth()->user();

            AuditLog::create([
                'actor_type' => $actor ? get_class($actor) : null,
                'actor_id'   => $actor?->id,

                'action'     => $request->route()?->getActionName(),
                'entity'     => $request->route('complaint') ? 'Complaint' : null,
                'entity_id'  => $request->route('complaint')?->id,

                'method'     => $request->method(),
                'url'        => $request->fullUrl(),
                'ip'         => $request->ip(),

                'status_code'=> $response->status(),
                'success'    => $response->status() < 400,

                'payload'    => $request->except(['password']),
            ]);
        } catch (\Throwable $e) {
            // لا نكسر النظام أبداً
        }

        return $response;
    }
}
