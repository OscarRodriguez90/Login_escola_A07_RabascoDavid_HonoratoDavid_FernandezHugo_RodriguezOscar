Drop database if exists db_escola;
create database db_escola;

use db_escola;

-- Tabla de administradores
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(200),
  rol enum('admin', 'teacher', 'student') NOT NULL DEFAULT 'teacher',
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Tabla de alumnos
CREATE TABLE students (
  id SERIAL PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  birth_date DATE,
  email VARCHAR(200) UNIQUE,
  class VARCHAR(50), -- ej.: 1A, 2B
  extra JSONB, -- campo flexible para datos adicionales
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- Tabla de asignaturas
CREATE TABLE subjects (
  id SERIAL PRIMARY KEY,
  code VARCHAR(50) UNIQUE NOT NULL,
  name VARCHAR(200) NOT NULL
);

-- Tabla de notas
CREATE TABLE grades (
  id SERIAL PRIMARY KEY,
  student_id INTEGER NOT NULL REFERENCES students(id) ON DELETE CASCADE,
  subject_id INTEGER NOT NULL REFERENCES subjects(id) ON DELETE RESTRICT,
  grade NUMERIC(5,2) NOT NULL CHECK (grade >= 0 AND grade <= 10),
  date_entered TIMESTAMP WITH TIME ZONE DEFAULT now(),
  UNIQUE(student_id, subject_id) -- si se desea sólo una nota por alumno/asignatura
);