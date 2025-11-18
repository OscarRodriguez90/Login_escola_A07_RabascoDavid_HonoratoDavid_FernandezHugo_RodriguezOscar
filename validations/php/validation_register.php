<?php

if (filter_has_var(INPUT_POST, 'register')) {

    if ($_POST['username'] == "" || $_POST['email'] == "" || $_POST['password'] == "" || $_POST['confirma_password'] == "") {

        header("Location: ../../pages/register.php?error=vacio");
        exit();

    } elseif (strlen($_POST['username']) > 15 || strlen($_POST['password']) > 15) {

        header("Location: ../../pages/register.php?error=largo");
        exit();


    } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST['email'])) {

        header("Location: ../../pages/register.php?error=email");
        exit();

    } elseif ($_POST['password'] != $_POST['confirma_password']) {

        header("Location: ../../pages/register.php?error=contra");
        exit();

    } else {

        session_start();

        include_once('../../db/conexion.php');

        $_SESSION['username'] = $_POST['username'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = $_POST['password'];

        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $passwordhash = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO usuarios (nombre_usuario, correo, contrasena) VALUES (:username, :email, :passwordhash)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':passwordhash' => $passwordhash

            ]);

            header("Location: ../../pages/login.php");
            exit();

        } catch (PDOException $e) {

            echo "Error al insertar el usuario";
            die();

        }

    }

} else {

    header("Location: ../index.php?error=register");
    exit();

}

?>