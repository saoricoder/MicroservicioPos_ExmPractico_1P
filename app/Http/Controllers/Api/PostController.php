<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => $request->get('user_id'), // ID inyectado por middleware
        ]);

        return response()->json($post, Response::HTTP_CREATED);
    }

    public function show(Post $post)
    {
        return response()->json($post, Response::HTTP_OK);
    }

    public function update(Request $request, Post $post)
    {
        // 1. Obtener ID del usuario del token
        $currentUserId = $request->get('user_id');

        // 2. Verificar permisos (Comparación estricta de enteros)
        if ((int)$post->user_id !== (int)$currentUserId) {
            return response()->json([
                'message' => 'No tienes permisos para editar este post'
            ], 403);
        }

        // 3. Validar datos nuevos
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        // 4. ACTUALIZAR (Esto faltaba en tu código)
        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        return response()->json($post, Response::HTTP_OK);
    }

    public function destroy(Request $request, Post $post)
    {
        // 1. Obtener ID del usuario del token
        $currentUserId = $request->get('user_id');

        // 2. Verificar permisos
        if ((int)$post->user_id !== (int)$currentUserId) {
            return response()->json([
                'message' => 'No tienes permisos para eliminar este post'
            ], 403);
        }

        // 3. ELIMINAR (Esto faltaba en tu código)
        $post->delete();

        // Retornamos 200 con mensaje o 204 sin contenido
        return response()->json(['message' => 'Post eliminado correctamente'], Response::HTTP_OK);
    }
}
