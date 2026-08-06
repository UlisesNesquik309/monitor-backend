-- 01_schema.sql
-- Autor: (pon aquí tu nombre)
-- Qué hace: crea la base de datos y las tablas del sistema.
-- Este archivo NO inserta datos, solo la estructura.

CREATE DATABASE IF NOT EXISTS monitor_sistema
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE monitor_sistema;

-- Tabla de usuarios (login / registro)
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    correo      VARCHAR(150) NOT NULL UNIQUE,
    contrasena  VARCHAR(255) NOT NULL,
    creado_en   DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de lecturas de sistema (histórico de CPU/RAM/Disco)
-- Una fila = una lectura completa (no una fila por métrica)
CREATE TABLE IF NOT EXISTS lecturas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    cpu         DECIMAL(5,2) NOT NULL,
    ram         DECIMAL(5,2) NOT NULL,
    disco       DECIMAL(5,2) NOT NULL,
    fecha_hora  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Índice para que las consultas por rango de fechas sean rápidas
CREATE INDEX idx_lecturas_fecha ON lecturas (fecha_hora);
