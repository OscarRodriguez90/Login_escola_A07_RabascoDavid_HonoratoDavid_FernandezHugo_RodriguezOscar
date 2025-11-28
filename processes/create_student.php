<?php
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol_id = $_POST['rol_id'] ?? null;
    $usuario = $_POST['usuario'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $apellidos = $_POST['apellidos'] ?? null;
    $email = $_POST['email'] ?? null;
    $contraseña = $_POST['contraseña'] ?? null;
    
    // Datos de matrícula
    $grupo_id = $_POST['grupo_id'] ?? null;
    $año_academico = $_POST['año_academico'] ?? null;

    if (!$rol_id || !$usuario || !$nombre || !$apellidos || !$email || !$contraseña || !$grupo_id || !$año_academico) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    try {
        $conn->beginTransaction();

        // 1. Crear Usuario
        $sql_user = "INSERT INTO usuarios (rol_id, usuario, nombre, apellidos, email, contraseña, fecha_creacion) 
                     VALUES (:rol_id, :usuario, :nombre, :apellidos, :email, :contrasena, NOW())";
        
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->execute([
            ':rol_id' => $rol_id,
            ':usuario' => $usuario,
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':email' => $email,
            ':contrasena' => password_hash($contraseña, PASSWORD_DEFAULT)
        ]);
        
        $usuario_id = $conn->lastInsertId();

        // 2. Crear Matrícula
        $sql_matricula = "INSERT INTO matriculas (usuario_id, grupo_id, año_academico) 
                          VALUES (:usuario_id, :grupo_id, :ano_academico)";
        
        $stmt_matricula = $conn->prepare($sql_matricula);
        $stmt_matricula->execute([
            ':usuario_id' => $usuario_id,
            ':grupo_id' => $grupo_id,
            ':ano_academico' => $año_academico
        ]);

        $conn->commit();
        
        header("Location: ../pages/home.php?msg=Alumno creado correctamente");
        exit;

    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error al crear alumno: " . $e->getMessage();
    }
} else {
    header("Location: ../pages/home.php");
}
?>
