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
    <h2>Notas del alumno</h2>
    <form method="post">
        <table border="1" cellpadding="5" style="border-collapse:collapse;">
            <tr>
                <th>Asignatura</th>
                <th>Nota</th>
            </tr>
            <?php foreach ($notas as $nota): ?>
                <tr>
                    <td><?= htmlspecialchars($nota['asignatura']) ?></td>
                    <td>
                        <input type="number" step="0.01" name="notas[<?= $nota['asignatura_id'] ?>]" value="<?= htmlspecialchars($nota['calificacion'] ?? '') ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <button type="submit">Guardar Notas</button>
    </form>
    <br>
    <a href="home.php">Volver a la lista de alumnos</a>
</body>
</html>
