<?php
require_once './../db/conexion.php';

try {
    // --- LÓGICA DE ORDENACIÓN ---
    $order = "usuarios.id ASC";
    if (isset($_POST['nombre_asc'])) $order = "usuarios.nombre ASC";
    if (isset($_POST['nombre_desc'])) $order = "usuarios.nombre DESC";
    if (isset($_POST['usuario_asc'])) $order = "usuarios.usuario ASC";
    if (isset($_POST['usuario_desc'])) $order = "usuarios.usuario DESC";
    if (isset($_POST['email_asc'])) $order = "usuarios.email ASC";
    if (isset($_POST['email_desc'])) $order = "usuarios.email DESC";
    if (isset($_POST['grupo_asc'])) $order = "grupos.nombre ASC";
    if (isset($_POST['grupo_desc'])) $order = "grupos.nombre DESC";
    if (isset($_POST['año_asc'])) $order = "matriculas.año_academico ASC";
    if (isset($_POST['año_desc'])) $order = "matriculas.año_academico DESC";

    // --- MANEJO DE LA BÚSQUEDA ---
    $busqueda = $_REQUEST['query'] ?? ''; 

    // --- PAGINACIÓN ---
    $limit = $_GET['limit'] ?? 10;
    $pagina = $_GET['pagina'] ?? 1;
    $offset = ($pagina - 1) * $limit;

    $resultados = [];
    $consulta = null;
    $total_registros_filtrados = 0;

    if (!empty($busqueda)) {
        // Consulta con FILTRO
        $consulta = $conn->prepare("
            SELECT usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, grupos.nombre AS grupo, matriculas.año_academico
            FROM usuarios
            INNER JOIN roles ON usuarios.rol_id = roles.id
            LEFT JOIN matriculas ON usuarios.id = matriculas.usuario_id
            LEFT JOIN grupos ON matriculas.grupo_id = grupos.id
            WHERE roles.nombre = 'alumno'
            AND (
                usuarios.usuario LIKE :busqueda OR
                usuarios.nombre LIKE :busqueda OR
                usuarios.apellidos LIKE :busqueda OR
                usuarios.email LIKE :busqueda OR
                grupos.nombre LIKE :busqueda OR
                matriculas.año_academico LIKE :busqueda
            )
            ORDER BY $order
            LIMIT :limit OFFSET :offset
        ");

        $consulta->bindValue(':busqueda', '%' . $busqueda . '%');
        $consulta->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $consulta->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $consulta->execute();
        $resultados = $consulta->fetchAll();

        // Contar registros filtrados
        $contador_filtro = $conn->prepare("
            SELECT COUNT(*)
            FROM usuarios
            INNER JOIN roles ON usuarios.rol_id = roles.id
            LEFT JOIN matriculas ON usuarios.id = matriculas.usuario_id
            LEFT JOIN grupos ON matriculas.grupo_id = grupos.id
            WHERE roles.nombre = 'alumno'
            AND (
                usuarios.usuario LIKE :busqueda OR
                usuarios.nombre LIKE :busqueda OR
                usuarios.apellidos LIKE :busqueda OR
                usuarios.email LIKE :busqueda OR
                grupos.nombre LIKE :busqueda OR
                matriculas.año_academico LIKE :busqueda
            )
        ");
        $contador_filtro->execute([':busqueda' => '%' . $busqueda . '%']);
        $total_registros_filtrados = $contador_filtro->fetchColumn();
    } else {
        // Consulta SIN FILTRO
        $consulta = $conn->prepare("
            SELECT usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, grupos.nombre AS grupo, matriculas.año_academico
            FROM usuarios
            INNER JOIN roles ON usuarios.rol_id = roles.id
            LEFT JOIN matriculas ON usuarios.id = matriculas.usuario_id
            LEFT JOIN grupos ON matriculas.grupo_id = grupos.id
            WHERE roles.nombre = 'alumno'
            ORDER BY $order
            LIMIT :limit OFFSET :offset
        ");

        $consulta->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $consulta->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $consulta->execute();
        $resultados = $consulta->fetchAll();
    }

    // Contar total general
    $consulta_total = $conn->query("SELECT COUNT(*) FROM usuarios INNER JOIN roles ON usuarios.rol_id = roles.id WHERE roles.nombre = 'alumno'");
    $registros_totales = $consulta_total->fetchColumn();

    // Total páginas
    $total_paginas = ceil(($busqueda ? $total_registros_filtrados : $registros_totales) / $limit);

} catch (PDOException $e) {
    echo "<div style='color:red;'>¡Error en la consulta!<br>" . $e->getMessage() . "</div>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Alumnos</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body class="CRUD">
    <header class="main_header">
        <img class="logo_img" src="../img/logo1.png" alt="Logo">
    </header>
    
    <div class="contenedor">
        <div class="header_crud_row">
            <div class="create_button"> 
                <form class="search_form_flex" method="GET" action="">
                    <input class="search_input" type="search" name="query" placeholder="Buscar" value="<?= htmlspecialchars($busqueda) ?>">
                    <button class="search_btn" type="submit">Buscar</button>
                    <a class="button_c clear_btn" href="./home.php">Limpiar</a>
                    <a href="../pages/notes_mitjanes.php" class="edit_btn">Notas medianas alumnos</a>
                </form>
            </div>

            <!-- SELECTOR DE ALUMNOS POR PÁGINA -->
            <form method="GET">
                <label>Mostrar:</label>
                <select name="limit" onchange="this.form.submit()">
                    <?php foreach ([5,10,20,50] as $opt): ?>
                    <option value="<?= $opt ?>" <?= ($limit == $opt ? 'selected' : '') ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select> alumnos
                <input type="hidden" name="query" value="<?= htmlspecialchars($busqueda) ?>">
            </form>

            <div class="cambiar_añadir">
                <a class="button_c create_btn" href="./form_crear.php">Añadir Alumno</a>
                <h3>Total registros: <?= $busqueda ? $total_registros_filtrados : $registros_totales ?></h3>
            </div>
        </div>
    </div>
    
    <div class="table_responsive_container">
        <table>
            <thead class="thead">
                <tr>
                    <form method="POST" action="?query=<?= urlencode($busqueda) ?>&limit=<?= $limit ?>">
                        <th><input type="submit" value="⬆" name="usuario_asc">Usuario<input type="submit" value="⬇" name="usuario_desc"></th>
                        <th><input type="submit" value="⬆" name="nombre_asc">Nombre<input type="submit" value="⬇" name="nombre_desc"></th>
                        <th>Apellidos</th>
                        <th><input type="submit" value="⬆" name="email_asc">Email<input type="submit" value="⬇" name="email_desc"></th>
                        <th><input type="submit" value="⬆" name="grupo_asc">Grupo<input type="submit" value="⬇" name="grupo_desc"></th>
                        <th><input type="submit" value="⬆" name="año_asc">Año<input type="submit" value="⬇" name="año_desc"></th>
                        <th>Acciones</th>
                    </form>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $columna): ?>
                    <tr>
                        <td>
                            <form action="./datos_alumno.php" method="get" style="display:inline;">
                                <input type="hidden" name="id-usuario" value="<?= htmlspecialchars($columna['id']) ?>">
                                <button id="btn-usuario-tabla" type="submit">
                                    <?= htmlspecialchars($columna['usuario']) ?>
                                </button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($columna['nombre']) ?></td>
                        <td><?= htmlspecialchars($columna['apellidos']) ?></td>
                        <td><?= htmlspecialchars($columna['email']) ?></td>
                        <td><?= htmlspecialchars($columna['grupo'] ?? '') ?></td>
                        <td><?= htmlspecialchars($columna['año_academico'] ?? '') ?></td>
                        <td>
                            <a href="../processes/eliminar.php?id=<?= $columna['id'] ?>" class="delete_btn">Eliminar</a>
                            <a href="form_modify.php?id=<?= $columna['id'] ?>" class="edit_btn">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <div style="text-align:center; margin-top:15px;">
        <a href="?pagina=<?= max(1, $pagina - 1) ?>&limit=<?= $limit ?>&query=<?= urlencode($busqueda) ?>">⬅ Anterior</a>
        <strong> Página <?= $pagina ?> / <?= $total_paginas ?> </strong>
        <a href="?pagina=<?= min($total_paginas, $pagina + 1) ?>&limit=<?= $limit ?>&query=<?= urlencode($busqueda) ?>">Siguiente ➡</a>
    </div>

</body>
</html>
