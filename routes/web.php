<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SnippetController;
Route::get('/', function () {
    return view('welcome');
});
// Routes pour outils API (Postman, etc.)
Route::prefix('api')->group(function () {
    Route::get('/snippets', [SnippetController::class, 'index']);
    Route::post('/snippets', [SnippetController::class, 'store']);
});