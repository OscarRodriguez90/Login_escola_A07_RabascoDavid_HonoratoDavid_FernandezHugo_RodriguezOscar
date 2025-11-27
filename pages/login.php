<?php
session_start();

// Mostrar mensaje de error si viene por parámetro

$error = $_GET['error'] ?? '';
$input_username = $_GET['username'] ?? '';
$msgerror = '';

switch ($error) {
    case 'campos_vacios':
        $msgerror = 'Por favor, complete todos los campos.';
        break;
    case 'usuario':
        $msgerror = 'Usuario no encontrado.';
        break;
    case 'pass':
        $msgerror = 'Contraseña incorrecta.';
        break;
    case 'error_bd':
        $msgerror = 'Error en la base de datos.';
        break;
}


// Si el usuario ya está autenticado, mostrar página de inicio
if (isset($_SESSION["id_usuario"])) {
    header('Location: ./home.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <!-- <h2 class="text-center">Pagina login</h2> -->
    <div class="container">
        <div class="logo_btns_row">
            <div class="logo_col">
                <img src="./../img/logo1.png" alt="logo" class="logo">
            </div>

            <div class="btns_col">

                <?php if ($msgerror): ?>
                    <p class="msg-error"><?= htmlspecialchars($msgerror) ?></p>
                <?php endif; ?>

                <form action="./../validations/php/validation_login.php" method="POST">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" onblur="validarUsername()" value="<?= htmlspecialchars($input_username) ?>">
                    <p id=username-error></p>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password">
                    <p id=password-error></p>
                    <br><br>
                    <div class="d-grid mb-3">
                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                        <br>
                        <a href="./../index.php" class="btn btn-secondary">Volver</a>
                </form>
            </div>

        </div>
    </div>
    <script src="../validations/js/validaciones.js" type="text/javascript" ></script>
</body>
</html>