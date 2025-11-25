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
  (1, 'admin01', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Ana', 'García López', 'ana.admin@escola.com'),      -- Administrador
  (2, 'profe01', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Luis', 'Martínez Ruiz', 'luis.profe@escola.com');   -- Profesor


INSERT INTO usuarios (rol_id, usuario, contraseña, nombre, apellidos, email) VALUES
(3, 'alumno01', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Marta', 'Sánchez Pérez', 'marta.alumno@escola.com'), -- Alumno
(3, 'alumno02', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Carlos', 'Ruiz Gómez', 'carlos.ruiz@escola.com'),
(3, 'alumno03', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Lucía', 'Fernández Díaz', 'lucia.fernandez@escola.com'),
(3, 'alumno04', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Javier', 'Martín López', 'javier.martin@escola.com'),
(3, 'alumno05', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Sofía', 'García Torres', 'sofia.garcia@escola.com'),
(3, 'alumno06', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'David', 'Sánchez Romero', 'david.sanchez@escola.com'),
(3, 'alumno07', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Paula', 'Jiménez Castro', 'paula.jimenez@escola.com'),
(3, 'alumno08', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Alejandro', 'Moreno Molina', 'alejandro.moreno@escola.com'),
(3, 'alumno09', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Elena', 'Navarro Ramos', 'elena.navarro@escola.com'),
(3, 'alumno10', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Miguel', 'Domínguez Ruiz', 'miguel.dominguez@escola.com'),
(3, 'alumno11', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Laura', 'Vega Sánchez', 'laura.vega@escola.com'),
(3, 'alumno12', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Andrés', 'Pérez Gómez', 'andres.perez@escola.com'),
(3, 'alumno13', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Marina', 'López Díaz', 'marina.lopez@escola.com'),
(3, 'alumno14', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Iván', 'García Sánchez', 'ivan.garcia@escola.com'),
(3, 'alumno15', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Sara', 'Martínez Torres', 'sara.martinez@escola.com'),
(3, 'alumno16', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Raúl', 'Jiménez Romero', 'raul.jimenez@escola.com'),
(3, 'alumno17', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Patricia', 'Moreno Castro', 'patricia.moreno@escola.com'),
(3, 'alumno18', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Hugo', 'Navarro Molina', 'hugo.navarro@escola.com'),
(3, 'alumno19', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Carmen', 'Domínguez Ramos', 'carmen.dominguez@escola.com'),
(3, 'alumno20', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Alberto', 'Vega Ruiz', 'alberto.vega@escola.com'),
(3, 'alumno21', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Natalia', 'Santos Pérez', 'natalia.santos@escola.com'),
(3, 'alumno22', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Jorge', 'Castro Gómez', 'jorge.castro@escola.com'),
(3, 'alumno23', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Isabel', 'Molina Díaz', 'isabel.molina@escola.com'),
(3, 'alumno24', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Víctor', 'Ruiz Sánchez', 'victor.ruiz@escola.com'),
(3, 'alumno25', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Rocío', 'Gómez Torres', 'rocio.gomez@escola.com'),
(3, 'alumno26', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Francisco', 'López Romero', 'francisco.lopez@escola.com'),
(3, 'alumno27', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Beatriz', 'Sánchez Castro', 'beatriz.sanchez@escola.com'),
(3, 'alumno28', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Manuel', 'Martínez Molina', 'manuel.martinez@escola.com'),
(3, 'alumno29', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Clara', 'Jiménez Ramos', 'clara.jimenez@escola.com'),
(3, 'alumno30', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Pablo', 'Moreno Ruiz', 'pablo.moreno@escola.com'),
(3, 'alumno31', '$2y$10$1V55cHganLYvESJc9PRiCui51d9/ldXBg5vDhqwfYZVGBfCaIlhSi', 'Silvia', 'Navarro Pérez', 'silvia.navarro@escola.com');
