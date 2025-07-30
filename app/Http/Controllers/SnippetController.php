<?php

namespace App\Http\Controllers;

use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class SnippetController extends Controller
{
    /**
     * Récupérer tous les snippets
     */
    public function index()
    {
        try {
            $snippets = Snippet::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $snippets,
                'count' => $snippets->count()
            ])->header('Access-Control-Allow-Origin', '*')
              ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
              ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
              
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des snippets: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur serveur'
            ], 500)->header('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Créer un nouveau snippet
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:PHP,HTML,CSS,JavaScript,Python,Java',
                'code' => 'required|string',
            ]);

            $snippet = Snippet::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Snippet créé avec succès',
                'data' => $snippet
            ], 201)->header('Access-Control-Allow-Origin', '*')
                   ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                   ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422)->header('Access-Control-Allow-Origin', '*');
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du snippet: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur serveur'
            ], 500)->header('Access-Control-Allow-Origin', '*');
        }
    }

    // Appliquez les mêmes headers aux autres méthodes...
}