<?php
require_once '../db/conexion.php';

$grupos = [];
$rol_alumno_id = null;

try {
    // Obtener grupos
    $stmt = $conn->query("SELECT * FROM grupos");
    $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener ID del rol 'alumno'
    $stmt_rol = $conn->prepare("SELECT id FROM roles WHERE nombre = 'alumno'");
    $stmt_rol->execute();
    $rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);
    $rol_alumno_id = $rol['id'] ?? null;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Alumno</title>
    <link rel="stylesheet" href="../styles/styles.css"> 
</head>
<body id="home_body">
    <header class="main_header">
        <div class="logo_container">
            <img src="../img/logo1.png" alt="logo de la aplicación" class="logo_img">
        </div>

        <div class="user_info">
            <span class="username">Administrador</span>
        </div>

        <div class="logout_container">
            <a href="../index.php" class="logout_btn" style="text-decoration: none;">Cerrar Sesión</a>
        </div>
    </header>

    <main class="admin-content">
        <h2 class="form-title">Añadir Nuevo Alumno</h2>

        <form action="../processes/create_student.php" method="POST" class="user-edit-form">
            
            <!-- El rol se envía oculto -->
            <input type="hidden" name="rol_id" value="<?= htmlspecialchars($rol_alumno_id ?? '') ?>">

            <div class="form-row">
                <div class="form-group-main">
                    <label for="usuario">Usuario (Login):</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                
                <div class="form-group-main">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group-main">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group-main">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group-main">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" required>
                </div>
                
                <div class="form-group-main">
                    <label for="año_academico">Año Académico:</label>
                    <input type="text" id="año_academico" name="año_academico" placeholder="Ej: 2023-2024" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group-main full-width">
                    <label for="grupo_id">Grupo:</label>
                    <select id="grupo_id" name="grupo_id" required>
                        <option value="">Seleccione un grupo</option>
                        <?php foreach ($grupos as $grupo): ?>
                            <option value="<?= $grupo['id'] ?>">
                                <?= htmlspecialchars($grupo['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="save-main-btn">Crear Alumno</button>
                <a href="./home.php" class="cancel-main-btn">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
