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

        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $passwordhash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO usuarios (rol_id, usuario, email, contraseña) VALUES (:rol, :username, :email, :passwordhash)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':rol' => 3, //Por defecto alumno
                ':username' => $username,
                ':email' => $email,
                ':passwordhash' => $passwordhash

            ]);

            header("Location: ../../pages/login.php");
            exit();

        } catch (PDOException $e) {
            //Mostrar error
            echo "Error: " . $e->getMessage();
            die();

        }

    }

} else {

    header("Location: ../index.php?error=register");
    exit();

}

?>