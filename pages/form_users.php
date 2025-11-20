<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Inicio</title>
    <link rel="stylesheet" href="../styles/styles.css"> 
</head>
<body id="home_body">
    <header class="main_header">
        <div class="logo_container">
            <img src="../img/logo1.png" alt="logo de la aplicación" class="logo_img">
        </div>

        <div class="user_info">
            <span class="username">Bienvenido, **Nombre de Usuario**</span>
        </div>

        <div class="logout_container">
            <button class="logout_btn">Cerrar Sesión</button>
        </div>
    </header>

    <main class="admin-content">
        <h2 class="form-title">Editar Datos de Usuario Seleccionado</h2>

        <form action="" method="POST" class="user-edit-form">
            
            <input type="hidden" name="id">

            <div class="form-row">
                <div class="form-group-main">
                    <label for="rol_id">Rol ID:</label>
                    <input type="number" id="rol_id" name="rol_id">
                </div>
                
                <div class="form-group-main">
                    <label for="usuario">Usuario (Login):</label>
                    <input type="text" id="usuario" name="usuario">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group-main">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre">
                </div>
                
                <div class="form-group-main">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group-main">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>
                
                <div class="form-group-main">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña">
                </div>
            </div>
            
            <div class="form-group-main full-width">
                <label for="fecha_creacion">Fecha de Creación:</label>
                <input type="text" id="fecha_creacion" name="fecha_creacion" readonly>
            </div>

            <div class="button-container">
                <button type="submit" class="save-main-btn">Guardar Todos los Cambios</button>
                <a href="./home.php" class="cancel-main-btn">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>