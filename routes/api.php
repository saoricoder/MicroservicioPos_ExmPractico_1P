<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Ruta get retornar lista de posts
Route::get('/posts',[PostController::class,'index'])->middleware('auth.micro');


//Ruta post crear post
Route::post('/posts',[PostController::class,'store'])->middleware('auth.micro');

//Ruta get retornar detalle de un post
Route::get('/posts/{post}',[PostController::class,'show'])->middleware('auth.micro');

//Ruta put actualizar un post
Route::put('/posts/{post}',[PostController::class,'update'])->middleware('auth.micro');

//Ruta delete eliminar un post
Route::delete('/posts/{post}',[PostController::class,'destroy'])->middleware('auth.micro');

