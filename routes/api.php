<?php

use App\Http\Controllers\SnippetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/snippets', [SnippetController::class, 'store']); // Pour ajouter un snippet
Route::get('/snippets', [SnippetController::class, 'index']);   // Pour afficher tous les snippets