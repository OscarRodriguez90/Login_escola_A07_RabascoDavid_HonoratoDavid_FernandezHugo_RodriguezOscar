<?php
require_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $rol_id = $_POST['rol_id'] ?? null;
    $usuario = $_POST['usuario'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $apellidos = $_POST['apellidos'] ?? null;
    $email = $_POST['email'] ?? null;
    $contraseña = $_POST['contraseña'] ?? null;

    if (!$rol_id || !$usuario || !$nombre || !$apellidos || !$email) {
        echo "Todos los campos obligatorios deben ser completados.";
        exit;
    }

    try {
        if ($id) {
            // Update existing user
            $sql = "UPDATE usuarios SET rol_id = :rol_id, usuario = :usuario, nombre = :nombre, apellidos = :apellidos, email = :email";
            $params = [
                ':rol_id' => $rol_id,
                ':usuario' => $usuario,
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':id' => $id
            ];

            if (!empty($contraseña)) {
                $sql .= ", contraseña = :contrasena";
                $params[':contrasena'] = password_hash($contraseña, PASSWORD_DEFAULT);
            }

            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            header("Location: ../pages/home.php?msg=Usuario actualizado correctamente");
        } else {
            // Create new user
            $sql = "INSERT INTO usuarios (rol_id, usuario, nombre, apellidos, email, contraseña, fecha_creacion) VALUES (:rol_id, :usuario, :nombre, :apellidos, :email, :contrasena, NOW())";
            
            if (empty($contraseña)) {
                 echo "La contraseña es obligatoria para nuevos usuarios.";
                 exit;
            }

            $params = [
                ':rol_id' => $rol_id,
                ':usuario' => $usuario,
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':contrasena' => password_hash($contraseña, PASSWORD_DEFAULT)
            ];
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            header("Location: ../pages/home.php?msg=Usuario creado correctamente");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: ../pages/home.php");
}
?>
