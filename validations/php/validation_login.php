<?php
if (filter_has_var(INPUT_POST, 'login')) {

    session_start();
    include_once('../../db/conexion.php');

    // Sanear entrada
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones básicas
    if ($username === '' || $password === '') {
        header("Location: ../../pages/login.php?error=vacio");
        exit();
    }

    if (strlen($username) > 15 || strlen($password) > 15) {
        header("Location: ../../pages/login.php?error=largo");
        exit();
    }

    try {
        // Prepared statement seguro
        $sql = "SELECT id, usuario, contraseña FROM usuarios WHERE usuario = :username LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $login = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$login) {
            // Usuario no encontrado
            header("Location: ../../pages/login.php?error=usuario");
            exit();
        }

        // Verificar contraseña usando password_verify
        if (password_verify($password, $login['contraseña'])) {
            // Login correcto
            $_SESSION['username'] = $login['usuario'];
            $_SESSION['id_user'] = (int)$login['id'];

            header("Location: ../../pages/home.php?Bienvenido");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: ../../pages/login.php?error=pass");
            exit();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
            die();
        // error_log("Error login: " . $e->getMessage());
        // header("Location: ../../pages/login.php?error=bd");
        // exit();
    }

} else {
    header("Location: ../index.php?error=NoLogin");
    exit();
}
