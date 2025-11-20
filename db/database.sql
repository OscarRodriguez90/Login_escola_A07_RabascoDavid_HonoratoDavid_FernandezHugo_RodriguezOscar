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









-- Insertar roles
INSERT INTO roles (nombre) VALUES ('administrador'), ('profesor'), ('alumno');

-- Insertar usuarios
INSERT INTO usuarios (rol_id, usuario, contraseña, nombre, apellidos, email)
VALUES
  (1, 'admin01', 'adminpass', 'Ana', 'García López', 'ana.admin@escola.com'),      -- Administrador
  (2, 'profe01', 'profepass', 'Luis', 'Martínez Ruiz', 'luis.profe@escola.com');   -- Profesor


  -- Inserta 10 alumnos de ejemplo
INSERT INTO usuarios (rol_id, usuario, contraseña, nombre, apellidos, email) VALUES
(3, 'alumno01', 'qazQAZ123', 'Marta', 'Sánchez Pérez', 'marta.alumno@escola.com'), -- Alumno
(3, 'alumno02', 'qazQAZ123', 'Carlos', 'Ruiz Gómez', 'carlos.ruiz@escola.com'),
(3, 'alumno03', 'qazQAZ123', 'Lucía', 'Fernández Díaz', 'lucia.fernandez@escola.com'),
(3, 'alumno04', 'qazQAZ123', 'Javier', 'Martín López', 'javier.martin@escola.com'),
(3, 'alumno05', 'qazQAZ123', 'Sofía', 'García Torres', 'sofia.garcia@escola.com'),
(3, 'alumno06', 'qazQAZ123', 'David', 'Sánchez Romero', 'david.sanchez@escola.com'),
(3, 'alumno07', 'qazQAZ123', 'Paula', 'Jiménez Castro', 'paula.jimenez@escola.com'),
(3, 'alumno08', 'qazQAZ123', 'Alejandro', 'Moreno Molina', 'alejandro.moreno@escola.com'),
(3, 'alumno09', 'qazQAZ123', 'Elena', 'Navarro Ramos', 'elena.navarro@escola.com'),
(3, 'alumno10', 'qazQAZ123', 'Miguel', 'Domínguez Ruiz', 'miguel.dominguez@escola.com'),
(3, 'alumno11', 'qazQAZ123', 'Laura', 'Vega Sánchez', 'laura.vega@escola.com'),
(3, 'alumno12', 'qazQAZ123', 'Andrés', 'Pérez Gómez', 'andres.perez@escola.com'),
(3, 'alumno13', 'qazQAZ123', 'Marina', 'López Díaz', 'marina.lopez@escola.com'),
(3, 'alumno14', 'qazQAZ123', 'Iván', 'García Sánchez', 'ivan.garcia@escola.com'),
(3, 'alumno15', 'qazQAZ123', 'Sara', 'Martínez Torres', 'sara.martinez@escola.com'),
(3, 'alumno16', 'qazQAZ123', 'Raúl', 'Jiménez Romero', 'raul.jimenez@escola.com'),
(3, 'alumno17', 'qazQAZ123', 'Patricia', 'Moreno Castro', 'patricia.moreno@escola.com'),
(3, 'alumno18', 'qazQAZ123', 'Hugo', 'Navarro Molina', 'hugo.navarro@escola.com'),
(3, 'alumno19', 'qazQAZ123', 'Carmen', 'Domínguez Ramos', 'carmen.dominguez@escola.com'),
(3, 'alumno20', 'qazQAZ123', 'Alberto', 'Vega Ruiz', 'alberto.vega@escola.com'),
(3, 'alumno21', 'qazQAZ123', 'Natalia', 'Santos Pérez', 'natalia.santos@escola.com'),
(3, 'alumno22', 'qazQAZ123', 'Jorge', 'Castro Gómez', 'jorge.castro@escola.com'),
(3, 'alumno23', 'qazQAZ123', 'Isabel', 'Molina Díaz', 'isabel.molina@escola.com'),
(3, 'alumno24', 'qazQAZ123', 'Víctor', 'Ruiz Sánchez', 'victor.ruiz@escola.com'),
(3, 'alumno25', 'qazQAZ123', 'Rocío', 'Gómez Torres', 'rocio.gomez@escola.com'),
(3, 'alumno26', 'qazQAZ123', 'Francisco', 'López Romero', 'francisco.lopez@escola.com'),
(3, 'alumno27', 'qazQAZ123', 'Beatriz', 'Sánchez Castro', 'beatriz.sanchez@escola.com'),
(3, 'alumno28', 'qazQAZ123', 'Manuel', 'Martínez Molina', 'manuel.martinez@escola.com'),
(3, 'alumno29', 'qazQAZ123', 'Clara', 'Jiménez Ramos', 'clara.jimenez@escola.com'),
(3, 'alumno30', 'qazQAZ123', 'Pablo', 'Moreno Ruiz', 'pablo.moreno@escola.com'),
(3, 'alumno31', 'qazQAZ123', 'Silvia', 'Navarro Pérez', 'silvia.navarro@escola.com');