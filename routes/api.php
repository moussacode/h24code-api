<?php

use App\Http\Controllers\SnippetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/snippets', [SnippetController::class, 'store']); // Pour ajouter un snippet
Route::get('/snippets', [SnippetController::class, 'index']);   // Pour afficher tous les snippets
// Route de test de l'API
Route::get('/test', function () {
    try {
        return response()->json([
            'status' => 'OK',
            'message' => 'API fonctionne',
            'timestamp' => now(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});