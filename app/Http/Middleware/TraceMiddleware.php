<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;

class TraceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            // تحديد الفاعل (Admin أو User)
            $actor = auth('admin')->user() ?? auth()->user();

            // 🔹 استخراج الكيان تلقائياً من Route Model Binding
            $entity = null;
            $entityId = null;

            foreach ($request->route()?->parameters() ?? [] as $param) {
                if (is_object($param) && method_exists($param, 'getKey')) {
                    $entity   = class_basename($param);
                    $entityId = $param->getKey();
                    break; // أول موديل فقط
                }
            }

            AuditLog::create([
                'actor_type' => $actor ? get_class($actor) : null,
                'actor_id'   => $actor?->id,

                'action'     => $request->route()?->getActionName(),

                'entity'     => $entity,
                'entity_id'  => $entityId,

                'method'     => $request->method(),
                'url'        => $request->fullUrl(),
                'ip'         => $request->ip(),

                'status_code'=> $response->status(),
                'success'    => $response->status() < 400,

                'payload' => $request->except(['password']) ?: null,

            ]);
        } catch (\Throwable $e) {
            logger()->error('AUDIT LOG ERROR: '.$e->getMessage());
        }


        return $response;
    }
}
