<?php


if (filter_has_var(INPUT_POST, 'login')) {


    session_start();

    include_once('../../db/conexion.php');
    
    $sql2 = "SELECT id_usuario, nombre_usuario, contrasena FROM usuarios WHERE nombre_usuario = '" . $_POST['username'] . "'";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute();
    $login = $stmt2->fetch(PDO::FETCH_ASSOC);
    // var_dump($login);
    // die();

    if (!$login) {
        
        header("Location: ../../pages/login.php?error=BBDD");
        exit();

    }  elseif ($_POST['username'] == "" || $_POST['password'] == "") {

        header("Location: ../../pages/login.php?error=vacio");
        exit();

    } elseif (strlen($_POST['username']) > 15 || strlen($_POST['password']) > 15){

        header("Location: ../../pages/login.php?error=largo");
        exit();

    } else {

        if (password_verify($_POST['password'], $login['password'])) {
            
            // Login correcto
            $_SESSION['username'] = $login['username'];
            $_SESSION['id_user'] = $login['id_user'];

            header("Location: ../index.php?Bienvenido");
            exit();
        
        } else {

            // Contraseña incorrecta
            header("Location: ../../pages/login.php?error=pass");
            exit();
        }
    }


} else {
    
    header("Location: ../index.php?error=NoLogin");
    exit();
} 