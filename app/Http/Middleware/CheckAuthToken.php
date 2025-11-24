<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized, token expired'], 401);
        }

        // Bypass de desarrollo (Opcional, mantenido de tu código original)
        $testToken = env('TEST_BYPASS_TOKEN');
        if ($testToken && $token === $testToken) {
            $user = ['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com'];
            $request->merge(['auth_user' => $user, 'user_id' => $user['id']]);
            return $next($request);
        }

        // 1. Configuración de la URL (Apunta al puerto 8001)
        $authBase = config('services.auth.url') ?? 'http://127.0.0.1:8001';
        $validateEndpoint = rtrim($authBase, '/') . '/api/validate-token';

        try {
            // 2. Consumir microservicio Auth
            $response = Http::timeout(3)->withToken($token)->get($validateEndpoint);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Authentication service unavailable'], 503);
        }

        if ($response->failed()) {
            return response()->json(['message' => 'Unauthorized, token invalid'], 401);
        }

        // 3. Extracción INTELIGENTE del usuario (Aquí estaba el riesgo)
        $data = $response->json();

        // Intentamos encontrar el ID ya sea en la raíz, dentro de 'user' o dentro de 'data'
        $userId = $data['id']
               ?? $data['user']['id']
               ?? $data['data']['id']
               ?? null;

        if ($userId) {
            // INYECTAR DATOS EN LA REQUEST
            // Esto permite usar $request->user_id en tus controladores
            $request->merge([
                'auth_user' => $data,     // Todo el objeto usuario
                'user_id'   => $userId,   // El ID suelto para comparaciones fáciles
            ]);

            return $next($request);
        }

        return response()->json(['message' => 'User data not found in token response'], 401);
    }
}
