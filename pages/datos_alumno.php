<?php
require_once './../db/conexion.php';

// Obtener el id del alumno desde GET
if (!isset($_GET['id-usuario']) || !is_numeric($_GET['id-usuario'])) {
    echo '<p>Id de alumno no válido.</p>';
    echo '<a href="home.php">Volver</a>';
    exit;
}
$id = (int)$_GET['id-usuario'];

// Obtener datos del alumno y su matrícula
$stmt = $conn->prepare('
    SELECT usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, grupos.nombre AS grupo, matriculas.año_academico, matriculas.id AS matricula_id
    FROM usuarios
    INNER JOIN roles ON usuarios.rol_id = roles.id
    LEFT JOIN matriculas ON usuarios.id = matriculas.usuario_id
    LEFT JOIN grupos ON matriculas.grupo_id = grupos.id
    WHERE usuarios.id = :id AND roles.nombre = "alumno"
');
$stmt->execute([':id' => $id]);
$alumno = $stmt->fetch();

if (!$alumno) {
    echo '<p>Alumno no encontrado.</p>';
    echo '<a href="home.php">Volver</a>';
    exit;
}

$matricula_id = $alumno['matricula_id'];
// Puedes ajustar asignatura_id y evaluacion_id según tu lógica o añadir selectores
$asignatura_id = 1;
$evaluacion_id = 1;

// Procesar formulario de notas (varias asignaturas)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $matricula_id) {
    if (isset($_POST['notas']) && is_array($_POST['notas'])) {
        foreach ($_POST['notas'] as $asignatura_id => $calificacion) {
            $calificacion = ($calificacion !== '') ? floatval($calificacion) : null;
            if ($calificacion !== null) {
                $stmtNota = $conn->prepare('INSERT INTO notas (matricula_id, asignatura_id, evaluacion_id, calificacion) VALUES (:matricula_id, :asignatura_id, 1, :calificacion) ON DUPLICATE KEY UPDATE calificacion = :calificacion');
                $stmtNota->execute([':matricula_id' => $matricula_id, ':asignatura_id' => $asignatura_id, ':calificacion' => $calificacion]);
            }
        }
        echo '<p>Calificaciones guardadas correctamente.</p>';
    }
}

// Obtener todas las asignaturas y notas del alumno
$notas = [];
if ($matricula_id) {
    $stmtNotas = $conn->prepare('
        SELECT a.id AS asignatura_id, a.nombre AS asignatura, n.calificacion
        FROM asignaturas a
        LEFT JOIN notas n ON n.asignatura_id = a.id AND n.matricula_id = :matricula_id AND n.evaluacion_id = 1
        ORDER BY a.nombre ASC
    ');
    $stmtNotas->execute([':matricula_id' => $matricula_id]);
    $notas = $stmtNotas->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos del Alumno</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body id="home_body">
    <header class="main_header">
        <img src="../img/logo.png" alt="Logo" class="logo_img" onerror="this.style.display='none'">
        <div class="user_info">
            Datos del Alumno
        </div>
        <a href="home.php" class="logout_btn">Volver</a>
    </header>
    <main>
        <div class="contenedor">
            <div class="admin-content">
                <h1 class="form-title">Datos del Alumno</h1>
                <div class="user-edit-form">
                    <div class="form-row">
                        <div class="form-group-main">
                            <label>Usuario</label>
                            <input type="text" value="<?= htmlspecialchars($alumno['usuario']) ?>" readonly>
                        </div>
                        <div class="form-group-main">
                            <label>Nombre</label>
                            <input type="text" value="<?= htmlspecialchars($alumno['nombre']) ?>" readonly>
                        </div>
                        <div class="form-group-main">
                            <label>Apellidos</label>
                            <input type="text" value="<?= htmlspecialchars($alumno['apellidos']) ?>" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group-main">
                            <label>Email</label>
                            <input type="text" value="<?= htmlspecialchars($alumno['email']) ?>" readonly>
                        </div>
                        <div class="form-group-main">
                            <label>Grupo</label>
                            <input type="text" value="<?= htmlspecialchars($alumno['grupo'] ?? '') ?>" readonly>
                        </div>
                        <div class="form-group-main">
                            <label>Año académico</label>
                            <input type="text" value="<?= htmlspecialchars($alumno['año_academico'] ?? '') ?>" readonly>
                        </div>
                    </div>
                </div>
                <h2 class="form-title" style="font-size:1.3rem;margin-top:2.5rem;">Notas del alumno</h2>
                <form method="post">
                    <div class="table_responsive_container">
                        <table>
                            <thead class="thead">
                                <tr>
                                    <th>Asignatura</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notas as $nota): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($nota['asignatura']) ?></td>
                                        <td>
                                            <input type="number" step="0.01" name="notas[<?= $nota['asignatura_id'] ?>]" value="<?= htmlspecialchars($nota['calificacion'] ?? '') ?>" class="search_input">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="save-main-btn">Guardar Notas</button>
                        <a href="home.php" class="cancel-main-btn">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
