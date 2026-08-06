<?php
/**
 * login.php
 * Autor: (pon aquí tu nombre)
 * Qué hace: valida correo + contraseña contra la BD y regresa los datos
 * mínimos del usuario. Front solo manda correo y contraseña (nada más).
 * Método esperado: POST
 * Body esperado: { "correo": "...", "contrasena": "..." }
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/utils/Response.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::noEncontrado('login');
}

$body = json_decode(file_get_contents("php://input"), true);

$correo      = isset($body['correo']) ? trim($body['correo']) : null;
$contrasena  = $body['contrasena'] ?? null;

if (!$correo || !$contrasena) {
    Response::error("LOGIN_DATOS_INCOMPLETOS", "Correo y contraseña son obligatorios", 400);
}

try {
    $database = new Database();
    $conn = $database->conectar();

    $stmt = $conn->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE correo = :correo LIMIT 1");
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
        Response::error("LOGIN_CREDENCIALES_INVALIDAS", "Correo o contraseña incorrectos", 401);
    }

    Response::success([
        "id"     => $usuario['id'],
        "nombre" => $usuario['nombre']
    ], "Login exitoso");

} catch (Exception $e) {
    Response::error("LOGIN_ERROR_SERVIDOR", "Error al procesar el login", 500);
}
