<?php
require_once '../db/conexion.php';

$id = $_GET['id'] ?? null;
$user = null;
$roles = [];

// Fetch roles
try {
    $stmt = $conn->query("SELECT * FROM roles");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching roles: " . $e->getMessage();
}

// Fetch user if ID is present
if ($id) {
    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching user: " . $e->getMessage();
    }
}
?>
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
        <h2 class="form-title"><?= $user ? 'Editar Datos de Usuario Seleccionado' : 'Crear Nuevo Usuario' ?></h2>

        <form action="../processes/update_users.php" method="POST" class="user-edit-form">
            
            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id'] ?? '') ?>">

            <div class="form-row">
                <div class="form-group-main">
                    <label for="rol_id">Rol:</label>
                    <select id="rol_id" name="rol_id" required>
                        <option value="">Seleccione un rol</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= ($user && $user['rol_id'] == $role['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group-main">
                    <label for="usuario">Usuario (Login):</label>
                    <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($user['usuario'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group-main">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" required>
                </div>
                
                <div class="form-group-main">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($user['apellidos'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group-main">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-group-main">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña" placeholder="Dejar en blanco para mantener la actual">
                </div>
            </div>
            
            <?php if ($user): ?>
            <div class="form-group-main full-width">
                <label for="fecha_creacion">Fecha de Creación:</label>
                <input type="text" id="fecha_creacion" name="fecha_creacion" value="<?= htmlspecialchars($user['fecha_creacion'] ?? '') ?>" readonly>
            </div>
            <?php endif; ?>

            <div class="button-container">
                <button type="submit" class="save-main-btn">Guardar Todos los Cambios</button>
                <a href="./home.php" class="cancel-main-btn">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>