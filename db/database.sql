DROP DATABASE IF EXISTS db_crud_escola_0616_A07;
CREATE DATABASE db_crud_escola_0616_A07;
USE db_crud_escola_0616_A07;

-- Tabla de roles: administrador, profesor, alumno
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    usuario VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (rol_id) REFERENCES roles(id)
) ENGINE=InnoDB;

-- Etapas educativas
CREATE TABLE etapas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- Cursos dentro de cada etapa
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etapa_id INT NOT NULL,
    nivel VARCHAR(50) NOT NULL,
    FOREIGN KEY (etapa_id) REFERENCES etapas(id)
) ENGINE=InnoDB;

-- Grupos por curso
CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    nombre VARCHAR(10) NOT NULL,
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
) ENGINE=InnoDB;

-- Asignaturas
CREATE TABLE asignaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    etapa_id INT NOT NULL,
    FOREIGN KEY (etapa_id) REFERENCES etapas(id)
) ENGINE=InnoDB;

-- Evaluaciones (se crea antes para usar en notas)
CREATE TABLE evaluaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    orden INT NOT NULL,
    porcentaje DECIMAL(5,2) NULL,
    visible_boletin BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- Matrículas
CREATE TABLE matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    grupo_id INT NOT NULL,
    año_academico VARCHAR(9) NOT NULL,
    UNIQUE(usuario_id, grupo_id, año_academico),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
) ENGINE=InnoDB;

-- Relación profesor -> asignatura -> grupo
CREATE TABLE asignaciones_profesores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profesor_id INT NOT NULL,
    asignatura_id INT NOT NULL,
    grupo_id INT NOT NULL,
    año_academico VARCHAR(9) NOT NULL,
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id),
    FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
) ENGINE=InnoDB;

-- Notas
CREATE TABLE notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula_id INT NOT NULL,
    asignatura_id INT NOT NULL,
    calificacion DECIMAL(5,2),
    evaluacion_id INT NOT NULL,
    FOREIGN KEY (matricula_id) REFERENCES matriculas(id),
    FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id),
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id)
) ENGINE=InnoDB;

-- Insertar roles
INSERT INTO roles (nombre) VALUES ('administrador'), ('profesor'), ('alumno');

-- Insertar usuarios
INSERT INTO usuarios (rol_id, usuario, contraseña, nombre, apellidos, email)
VALUES
  (1, 'admin01', 'qazQAZ123', 'Ana', 'García López', 'ana.admin@escola.com'),      -- Administrador
  (2, 'profe01', 'qazQAZ123', 'Luis', 'Martínez Ruiz', 'luis.profe@escola.com');   -- Profesor


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

/* ============================
   ETAPAS EDUCATIVAS
============================ */
INSERT INTO etapas (nombre) VALUES
('Primaria'),
('Secundaria'),
('Bachillerato');

/* ============================
   CURSOS (Ejemplo por etapa)
============================ */
INSERT INTO cursos (etapa_id, nivel) VALUES
(1, '5º Primaria'),
(1, '6º Primaria'),
(2, '1º ESO'),
(2, '2º ESO'),
(2, '3º ESO'),
(2, '4º ESO'),
(3, '1º Bachillerato'),
(3, '2º Bachillerato');

/* ============================
   GRUPOS
============================ */
INSERT INTO grupos (curso_id, nombre) VALUES
(3, 'A'),
(3, 'B'),
(4, 'A'),
(4, 'B'),
(5, 'A'),
(5, 'B');

/* ============================
   ASIGNATURAS
============================ */
INSERT INTO asignaturas (nombre, etapa_id) VALUES
('Matemáticas', 2),
('Lengua Castellana', 2),
('Inglés', 2),
('Biología', 2),
('Geografía e Historia', 2),
('Física y Química', 2);

/* ============================
   EVALUACIONES
============================ */
INSERT INTO evaluaciones (nombre, orden, porcentaje, visible_boletin) VALUES
('1ª Evaluación', 1, 33.33, TRUE),
('2ª Evaluación', 2, 33.33, TRUE),
('3ª Evaluación', 3, 33.33, TRUE),
('Ordinaria', 4, NULL, TRUE);

/* ============================
   MATRÍCULAS (20 alumnos en 1º ESO A)
   grupo_id = 1 (según arriba)
============================ */
INSERT INTO matriculas (usuario_id, grupo_id, año_academico) VALUES
(3, 1, '2024-2025'),
(4, 1, '2024-2025'),
(5, 1, '2024-2025'),
(6, 1, '2024-2025'),
(7, 1, '2024-2025'),
(8, 1, '2024-2025'),
(9, 1, '2024-2025'),
(10, 1, '2024-2025'),
(11, 1, '2024-2025'),
(12, 1, '2024-2025'),
(13, 1, '2024-2025'),
(14, 1, '2024-2025'),
(15, 1, '2024-2025'),
(16, 1, '2024-2025'),
(17, 1, '2024-2025'),
(18, 1, '2024-2025'),
(19, 1, '2024-2025'),
(20, 1, '2024-2025'),
(21, 1, '2024-2025'),
(22, 1, '2024-2025');

/* ============================
   ASIGNACIÓN PROFESOR → ASIGNATURA → GRUPO
   Asignamos al profesor `profe01` (id = 2)
============================ */
INSERT INTO asignaciones_profesores (profesor_id, asignatura_id, grupo_id, año_academico) VALUES
(2, 1, 1, '2024-2025'), -- Matemáticas
(2, 2, 1, '2024-2025'), -- Lengua
(2, 3, 1, '2024-2025'), -- Inglés
(2, 4, 1, '2024-2025'), -- Biología
(2, 5, 1, '2024-2025'), -- Geografía
(2, 6, 1, '2024-2025'); -- FyQ

/* ============================
   NOTAS (solo ejemplos básicos)
============================ */
INSERT INTO notas (matricula_id, asignatura_id, calificacion, evaluacion_id) VALUES
(1, 1, 7.5, 1),
(1, 2, 8.0, 1),
(1, 3, 6.2, 1),
(2, 1, 5.0, 1),
(2, 2, 4.5, 1),
(2, 3, 6.8, 1),
(3, 1, 9.0, 1),
(3, 2, 8.7, 1),
(3, 3, 7.4, 1);
