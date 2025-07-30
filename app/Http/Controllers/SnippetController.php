<?php
// 1. Migration pour créer la table snippets (si pas encore fait)
// php artisan make:migration create_snippets_table

// database/migrations/xxxx_xx_xx_create_snippets_table.php
namespace App\Http\Controllers;

use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class SnippetController extends Controller
{
    public function index()
    {
        try {
            $snippets = Snippet::orderBy('created_at', 'desc')->get();
            return response()->json($snippets);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des snippets: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Log pour debug
            Log::info('Données reçues:', $request->all());
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:PHP,HTML,CSS',
                'code' => 'required|string',
            ]);

            $snippet = Snippet::create($validated);
            
            Log::info('Snippet créé avec succès:', $snippet->toArray());
            
            return response()->json($snippet, 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du snippet: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }
}
