<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro</title>

  <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>

<div class="container">
    <div class="logo_btns_row">

        <!-- COLUMNA DEL LOGO -->
        <div class="logo_col">
            <img class="logo" src="../img/logo1.png" alt="logo">
        </div>

        <!-- FORMULARIO -->
        <div class="btns_col">

            <!-- Mensaje de error global JS -->
            <p id="clientErrorRegister" class="errorBox"></p>

            <form id="registerForm" action="../validations/php/validation_register.php" method="post" novalidate>
                <h2 style="text-align:center; margin-bottom:1rem;">Crear Cuenta</h2>

                <!-- USERNAME -->
                <label for="username">Usuario</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    placeholder="Usuario"
                    class="<?php if(isset($_GET['userError'])) echo 'invalid'; ?>"
                    value="<?php if(isset($_GET['username'])) echo htmlspecialchars($_GET['username']); ?>"
                >
                <!-- Errores PHP + JS -->
                <p id="usernameError" class="input-error">
                    <?php
                        if(isset($_GET['userError'])){
                            echo match($_GET['userError']){
                                "vacio" => "El usuario no puede estar vacío",
                                "largo" => "Máximo 15 caracteres",
                                "existe" => "Este usuario ya está registrado",
                                default => "Usuario inválido"
                            };
                        }
                    ?>
                </p>

                <!-- EMAIL -->
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="Email"
                    class="<?php if(isset($_GET['emailError'])) echo 'invalid'; ?>"
                    value="<?php if(isset($_GET['email'])) echo htmlspecialchars($_GET['email']); ?>"
                >
                <p id="emailError" class="input-error">
                    <?php
                        if(isset($_GET['emailError'])){
                            echo match($_GET['emailError']){
                                "existe" => "Este email ya está registrado",
                                "formato" => "Formato de email inválido",
                                "vacio" => "El email no puede estar vacío",
                                default => "Email inválido"
                            };
                        }
                    ?>
                </p>

                <!-- PASSWORD -->
                <label for="password">Contraseña</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Contraseña"
                    class="<?php if(isset($_GET['passError'])) echo 'invalid'; ?>"
                >
                <p id="passwordError" class="input-error">
                    <?php
                        if(isset($_GET['passError'])){
                            echo match($_GET['passError']){
                                "vacio" => "La contraseña no puede estar vacía",
                                "largo" => "Máximo 15 caracteres",
                                default => ""
                            };
                        }
                    ?>
                </p>

                <!-- CONFIRM PASSWORD -->
                <label for="confirma_password">Confirmar Contraseña</label>
                <input
                    id="confirma_password"
                    type="password"
                    name="confirma_password"
                    placeholder="Confirmar contraseña"
                    class="<?php if(isset($_GET['matchError'])) echo 'invalid'; ?>"
                >
                <p id="confirmError" class="input-error">
                    <?php if(isset($_GET['matchError'])) echo "Las contraseñas no coinciden"; ?>
                </p>

                <div class="d-grid">
                    <button type="submit" name="register">Registrarse</button>
                </div>
            </form>

            <form action="./index.php" method="get" style="margin-top: 1rem;">
                <button type="submit">Volver</button>
            </form>

        </div>

    </div>
</div>

<script src="../validations/js/validacion_registro.js"></script>
</body>
</html>
