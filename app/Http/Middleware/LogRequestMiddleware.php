<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware {
    public function handle(Request $req, Closure $next) {
        $userId = $req->user()?->id ?? null;
        Log::info('api-request', [
            'user_id'=>$userId,
            'endpoint'=>$req->path(),
            'method'=>$req->method(),
            'timestamp'=>now()->toDateTimeString()
        ]);
        return $next($req);
    }
}
