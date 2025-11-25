<?php
require_once './../db/conexion.php';

// Obtener el id del alumno desde GET
if (!isset($_GET['id-usuario']) || !is_numeric($_GET['id-usuario'])) {
    echo '<p>Id de alumno no válido.</p>';
    echo '<a href="home.php">Volver</a>';
    exit;
}
$id = (int)$_GET['id-usuario'];

// Obtener datos del alumno
$stmt = $conn->prepare('
    SELECT usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, grupos.nombre AS grupo, matriculas.año_academico
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

// Procesar formulario de notas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calificacion = isset($_POST['calificacion']) ? floatval($_POST['calificacion']) : null;
    if ($calificacion !== null) {
        // Guardar calificación en la base de datos (ejemplo: tabla notas)
        // NOTA: Aquí deberías especificar matricula_id, asignatura_id y evaluacion_id correctamente
        // Esto es solo un ejemplo genérico:
        $stmtNota = $conn->prepare('INSERT INTO notas (matricula_id, asignatura_id, evaluacion_id, calificacion) VALUES (:matricula_id, :asignatura_id, :evaluacion_id, :calificacion) ON DUPLICATE KEY UPDATE calificacion = :calificacion');
        // Debes obtener estos IDs de alguna manera, aquí se ponen valores de ejemplo:
        $stmtNota->execute([':matricula_id' => 1, ':asignatura_id' => 1, ':evaluacion_id' => 1, ':calificacion' => $calificacion]);
        echo '<p>Calificación guardada correctamente.</p>';
    }
}

// Obtener calificación actual (si existe)
$stmtNota = $conn->prepare('SELECT calificacion FROM notas WHERE matricula_id = 1 AND asignatura_id = 1 AND evaluacion_id = 1');
$stmtNota->execute();
$calificacionActual = $stmtNota->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos del Alumno</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <h1>Datos del Alumno</h1>
    <ul>
        <li><strong>Usuario:</strong> <?= htmlspecialchars($alumno['usuario']) ?></li>
        <li><strong>Nombre:</strong> <?= htmlspecialchars($alumno['nombre']) ?></li>
        <li><strong>Apellidos:</strong> <?= htmlspecialchars($alumno['apellidos']) ?></li>
        <li><strong>Email:</strong> <?= htmlspecialchars($alumno['email']) ?></li>
        <li><strong>Grupo:</strong> <?= htmlspecialchars($alumno['grupo'] ?? '') ?></li>
        <li><strong>Año académico:</strong> <?= htmlspecialchars($alumno['año_academico'] ?? '') ?></li>
    </ul>
    <h2>Nota</h2>
    <form method="post">
        <label for="calificacion">Nota:</label>
        <input type="number" step="0.01" name="calificacion" id="calificacion" value="<?= htmlspecialchars($calificacionActual ?? '') ?>">
        <button type="submit">Guardar Nota</button>
    </form>
    <br>
    <a href="home.php">Volver a la lista de alumnos</a>
</body>
</html>
