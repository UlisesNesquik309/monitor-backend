<?php
/**
 * Response.php
 * Autor: (pon aquí tu nombre)
 * Qué hace: centraliza el formato de TODAS las respuestas JSON del backend,
 * para que siempre se mande primero el "estado" y luego la información,
 * tal como lo pidió el profesor.
 *
 * Formato de éxito:
 * { "estado": true, "mensaje": "...", "data": {...} }
 *
 * Formato de error de una acción/función específica:
 * { "estado": false, "error": "NOMBRE_ERROR", "mensaje": "..." }
 *
 * Formato cuando el servicio (endpoint) no existe o el método no es válido:
 * { "estado": false, "mensaje": "Servicio no encontrado" }
 */

class Response
{
    /** Respuesta correcta */
    public static function success($data = null, string $mensaje = "Operación exitosa", int $codigoHttp = 200): void
    {
        http_response_code($codigoHttp);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "estado"  => true,
            "mensaje" => $mensaje,
            "data"    => $data
        ]);
        exit;
    }

    /**
     * Error de una acción concreta (ej: login fallido, datos incompletos, etc.)
     * $errorNombre debe describir qué acción falló, ej: "LOGIN_CREDENCIALES_INVALIDAS"
     */
    public static function error(string $errorNombre, string $mensaje = "Ocurrió un error", int $codigoHttp = 400): void
    {
        http_response_code($codigoHttp);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "estado"  => false,
            "error"   => $errorNombre,
            "mensaje" => $mensaje
        ]);
        exit;
    }

    /** Cuando el endpoint no existe o se llamó con un método HTTP no soportado */
    public static function noEncontrado(string $servicio = ""): void
    {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "estado"  => false,
            "mensaje" => "Servicio no encontrado" . ($servicio ? ": {$servicio}" : "")
        ]);
        exit;
    }
}
