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
    <div class="container">
        <div class="logo_btns_row">
            <div class="logo_col">
                <img src="./../img/logo1.png" alt="logo" class="logo">
            </div>

            <div class="btns_col">
                <form action="../validations/php/validation_register.php" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <p id=username-error></p>
                    <br><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <p id=email-error></p>
                    <br><br>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                    <p id=password-error></p>

                    <label for="confirma_password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm" name="confirma_password" required>
                    <p id=confirm-error></p>
                    <br><br>
                    <div class="d-grid mb-3">
                        <button type="submit" name="register" class="btn btn-primary">Registrarse</button>
                        <br>
                         <a href="./index.php" class="btn btn-secondary">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
     <script src="../validations/js/validacion_registro.js" type="text/javascript"></script>
</body>
</html>