<?php

if (!filter_has_var(INPUT_POST, 'register')) {
    header("Location: ../../pages/registro.php");
    exit();
}

$errores = "";
$queryData = [];

// USERNAME
if(isset($_POST['username']) && !empty(trim($_POST['username']))){
    $username = trim($_POST['username']);
    $queryData['username'] = $username;

    if(strlen($username) > 15){
        $errores .= ($errores ? '&' : '?') . 'userError=largo';
    }
} else {
    $errores .= ($errores ? '&' : '?') . 'userError=vacio';
}

// EMAIL
if(isset($_POST['email']) && !empty(trim($_POST['email']))){
    $email = trim($_POST['email']);
    $queryData['email'] = $email;

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errores .= ($errores ? '&' : '?') . 'emailError=formato';
    }

} else {
    $errores .= ($errores ? '&' : '?') . 'emailError=vacio';
}

// PASSWORD
if(isset($_POST['password']) && !empty($_POST['password'])){
    $password = $_POST['password'];

    if(strlen($password) > 15){
        $errores .= ($errores ? '&' : '?') . 'passError=largo';
    }

} else {
    $errores .= ($errores ? '&' : '?') . 'passError=vacio';
}

// CONFIRM PASSWORD
if($_POST['password'] !== $_POST['confirma_password']){
    $errores .= ($errores ? '&' : '?') . 'matchError=true';
}

// Si ya hay errores, redirige antes de consultar BBDD
if($errores !== ""){
    header("Location: ../../pages/registro.php".$errores."&".http_build_query($queryData));
    exit();
}

include_once('../../db/conexion.php');
session_start();

// 🔍 VALIDAR DUPLICADOS EN BASE DE DATOS

// Check Username
$checkUsername = $conn->prepare("SELECT usuario FROM usuarios WHERE usuario = :username LIMIT 1");
$checkUsername->execute([':username' => $username]);

if($checkUsername->rowCount() > 0){
    header("Location: ../../pages/registro.php?userError=existe&".http_build_query($queryData));
    exit();
}

// Check Email
$checkEmail = $conn->prepare("SELECT email FROM usuarios WHERE email = :email LIMIT 1");
$checkEmail->execute([':email' => $email]);

if($checkEmail->rowCount() > 0){
    header("Location: ../../pages/registro.php?emailError=existe&".http_build_query($queryData));
    exit();
}

// Insert if everything is valid
$passwordhash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (`rol_id`, `usuario`, `email`, `contraseña`) 
        VALUES (3, :username, :email, :passwordhash)";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':username' => $username,
    ':email' => $email,
    ':passwordhash' => $passwordhash
]);

$_SESSION['username'] = $username;
header("Location: ../../pages/login.php");
exit();


?>