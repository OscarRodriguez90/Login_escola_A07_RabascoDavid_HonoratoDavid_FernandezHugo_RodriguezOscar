CREATE DATABASE IF NOT EXISTS a06_escola
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE a06_escola;

-- ========================================================
-- 1. TABLA: usuarios
-- ========================================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'docente', 'secretaria') NOT NULL DEFAULT 'docente',
    fecha_careacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ========================================================
-- 2. TABLA: alumnos
-- ========================================================
CREATE TABLE alumnos (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    grado VARCHAR(50),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ========================================================
-- 3. TABLA: profesores
-- ========================================================
CREATE TABLE profesores (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(100),
    telefono VARCHAR(20),
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
      ON DELETE SET NULL ON UPDATE CASCADE
);

-- ========================================================
-- 4. TABLA: materias
-- ========================================================
CREATE TABLE materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre_materia VARCHAR(100) NOT NULL,
    descripcion TEXT,
    id_profesor INT,
    FOREIGN KEY (id_profesor) REFERENCES profesores(id_profesor)
      ON DELETE SET NULL ON UPDATE CASCADE
);

-- ========================================================
-- 5. TABLA: notas
-- ========================================================
CREATE TABLE notas (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_materia INT NOT NULL,
    periodo VARCHAR(20) NOT NULL,
    nota DECIMAL(5,2) CHECK (nota >= 0 AND nota <= 100),
    observaciones TEXT,
    FOREIGN KEY (id_alumno) REFERENCES alumnos(id_alumno)
      ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia)
      ON DELETE CASCADE ON UPDATE CASCADE
);