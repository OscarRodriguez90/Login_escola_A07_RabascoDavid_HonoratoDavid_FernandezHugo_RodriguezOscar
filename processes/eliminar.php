<?php
require_once '../db/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // 1. Eliminar NOTAS asociadas a las matrículas del usuario
        // Primero obtenemos los IDs de las matrículas del usuario
        $stmt_get_matriculas = $conn->prepare("SELECT id FROM matriculas WHERE usuario_id = :id");
        $stmt_get_matriculas->execute([':id' => $id]);
        $matriculas_ids = $stmt_get_matriculas->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($matriculas_ids)) {
            // Crear placeholders para la cláusula IN (?,?,?)
            $placeholders = implode(',', array_fill(0, count($matriculas_ids), '?'));
            $stmt_del_notas = $conn->prepare("DELETE FROM notas WHERE matricula_id IN ($placeholders)");
            $stmt_del_notas->execute($matriculas_ids);
        }

        // 2. Eliminar MATRÍCULAS del usuario
        $stmt_del_matriculas = $conn->prepare("DELETE FROM matriculas WHERE usuario_id = :id");
        $stmt_del_matriculas->execute([':id' => $id]);

        // 3. Eliminar ASIGNACIONES DE PROFESORES (si es profesor)
        $stmt_del_asignaciones = $conn->prepare("DELETE FROM asignaciones_profesores WHERE profesor_id = :id");
        $stmt_del_asignaciones->execute([':id' => $id]);

        // 4. Eliminar el USUARIO
        $stmt_del_usuario = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt_del_usuario->execute([':id' => $id]);

        // Confirmar cambios
        $conn->commit();

        // Redirigir de vuelta al CRUD
        header("Location: home.php?msg=deleted");
        exit();

    } catch (Exception $e) {
        // Si hay error, deshacer cambios
        $conn->rollBack();
        echo "Error al eliminar el usuario: " . $e->getMessage();
    }
} else {
    // Si no hay ID, volver al CRUD
    header("Location: home.php");
    exit();
}
?>
