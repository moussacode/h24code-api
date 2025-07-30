<?php
// debug.php - Placez ce fichier dans public/ temporairement
// Accès via : https://h24code-api.onrender.com/debug.php

echo "<h1>Debug Laravel sur Render</h1>";

// Informations système
echo "<h2>Informations Système</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' . "<br>";

// Extensions PHP
echo "<h2>Extensions PHP</h2>";
$required = ['pdo', 'pdo_pgsql', 'pgsql', 'mbstring', 'bcmath', 'gd'];
foreach ($required as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "$status $ext<br>";
}

// Variables d'environnement
echo "<h2>Variables d'environnement Laravel</h2>";
$env_vars = ['APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
foreach ($env_vars as $var) {
    $value = $_ENV[$var] ?? getenv($var) ?: 'NON DÉFINIE';
    if (strpos($var, 'PASSWORD') !== false) {
        $value = str_repeat('*', strlen($value));
    }
    echo "$var: $value<br>";
}

// Test Laravel
echo "<h2>Test Laravel</h2>";
try {
    // Vérifier si Laravel peut se charger
    if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
        echo "✅ Bootstrap Laravel trouvé<br>";
        
        require_once __DIR__ . '/../bootstrap/app.php';
        echo "✅ Laravel chargé<br>";
        
        // Test de la base de données
        if (class_exists('Illuminate\Support\Facades\DB')) {
            $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
            echo "✅ Connexion base de données OK<br>";
            
            $version = \Illuminate\Support\Facades\DB::select('SELECT version()')[0]->version;
            echo "PostgreSQL: $version<br>";
        }
    } else {
        echo "❌ Bootstrap Laravel non trouvé<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur Laravel: " . $e->getMessage() . "<br>";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "<br>";
}

// Fichiers importants
echo "<h2>Fichiers</h2>";
$files = [
    '.env' => __DIR__ . '/../.env',
    'routes/api.php' => __DIR__ . '/../routes/api.php',
    'app/Models' => __DIR__ . '/../app/Models',
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name existe<br>";
        if ($name === '.env') {
            echo "Taille: " . filesize($path) . " bytes<br>";
        }
    } else {
        echo "❌ $name manquant<br>";
    }
}

echo "<p><strong>⚠️ Supprimez ce fichier après debugging!</strong></p>";
?>