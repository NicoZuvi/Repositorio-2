<?php
/**
 * DEBUG MODE
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Obtener el módulo desde la URL. Por defecto, 'students'
$module = $_GET['module'] ?? 'students';

// Ruta esperada al archivo del módulo
$routeFile = "./routes/{$module}Routes.php";

// Verificamos si existe
if (file_exists($routeFile)) {
    require_once($routeFile);
} else {
    // Manejo de error 404
    http_response_code(404);
    echo json_encode([
        "error" => "404 - Ruta no encontrada para el módulo '{$module}'"
    ]);
    exit();
}
?>