<?php
/**
 * database.php
 * Autor: (Jose Ulises Robledo Gutierrez)
 * Qué hace: crea la conexión PDO hacia MySQL. El host "db" es el nombre
 * del servicio de MySQL dentro de docker-compose.yml (no uses "localhost"
 * si trabajas con Docker).
 */

class Database
{
    private string $host    = "db";
    private string $db_name = "monitor_sistema";
    private string $username = "root";
    private string $password = "root";
    public ?PDO $conn = null;

    public function conectar(): ?PDO
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error de conexión a la BD: " . $e->getMessage());
        }
        return $this->conn;
    }
}
