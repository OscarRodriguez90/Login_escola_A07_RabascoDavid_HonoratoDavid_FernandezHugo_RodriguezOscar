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
    // Usamos el parámetro 'query' de REQUEST para capturar la búsqueda sin importar si se envió por GET o POST
    $busqueda = $_REQUEST['query'] ?? ''; 

    // Prepara los parámetros de consulta para la tabla (GET)
    $query_params = !empty($busqueda) ? "&query=" . urlencode($busqueda) : "";
    
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
        ");
        $consulta->execute([':busqueda' => '%' . $busqueda . '%']);
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
        $consulta = $conn->query("
            SELECT usuarios.id, usuarios.usuario, usuarios.nombre, usuarios.apellidos, usuarios.email, grupos.nombre AS grupo, matriculas.año_academico
            FROM usuarios
            INNER JOIN roles ON usuarios.rol_id = roles.id
            LEFT JOIN matriculas ON usuarios.id = matriculas.usuario_id
            LEFT JOIN grupos ON matriculas.grupo_id = grupos.id
            WHERE roles.nombre = 'alumno'
            ORDER BY $order
        ");
        $resultados = $consulta->fetchAll();
    }
    
    // Contar el total de registros (sin filtro)
    $consulta_total = $conn->query("SELECT COUNT(*) FROM usuarios INNER JOIN roles ON usuarios.rol_id = roles.id WHERE roles.nombre = 'alumno'");
    $registros_totales = $consulta_total->fetchColumn();

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
    <!-- Header Fijo -->
    <header class="main_header">
        <img class="logo_img" src="../img/logo1.png" alt="Logo">
    </header>
    
    <div class="contenedor">
        <div class="header_crud_row">
            
            <div class="create_button"> 
                <form class="search_form_flex" role="search" method="GET" action="">

                    <input class="search_input" type="search" name="query" placeholder="Buscar" aria-label="Buscar" value="<?= htmlspecialchars($busqueda) ?>">
                    <button class="search_btn" type="submit">Buscar</button>
                    <a class="button_c clear_btn" href="./crud.php">Limpiar</a>
                    <br>

                </form>
            </div>

            <div class="cambiar_añadir">
                <a class="button_c create_btn" href="./form_alumnos.php">Añadir Alumno</a>                
                <a class="button_c change_role_btn" href="./crud_profes.php">Cambiar a profes</a>
                
                <?php

                    if (!empty($busqueda)) {
                        echo "<h3>Total de registros: $total_registros_filtrados</h3>";
                    } else {
                        echo "<h3>Total de registros: $registros_totales</h3>";
                    }

                ?>
            </div>
        </div>
    </div>
    
    <div class="table_responsive_container">
        
        <form method="POST" action="?query=<?= urlencode($busqueda) ?>">
            <table>
                <thead class="thead">
                    <tr>
                        
                        <th><input type="submit" value="⬆" name="usuario_asc">Usuario<input type="submit" value="⬇" name="usuario_desc"></th>
                        <th><input type="submit" value="⬆" name="nombre_asc">Nombre<input type="submit" value="⬇" name="nombre_desc"></th>
                        <th>Apellidos</th>
                        <th><input type="submit" value="⬆" name="email_asc">Email<input type="submit" value="⬇" name="email_desc"></th>
                        <th><input type="submit" value="⬆" name="grupo_asc">Grupo<input type="submit" value="⬇" name="grupo_desc"></th>
                        <th><input type="submit" value="⬆" name="año_asc">Año<input type="submit" value="⬇" name="año_desc"></th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $columna): ?>
                        <tr>
                            <td><?= htmlspecialchars($columna['usuario']) ?></td>
                            <td><?= htmlspecialchars($columna['nombre']) ?></td>
                            <td><?= htmlspecialchars($columna['apellidos']) ?></td>
                            <td><?= htmlspecialchars($columna['email']) ?></td>
                            <td><?= htmlspecialchars($columna['grupo'] ?? '') ?></td>
                            <td><?= htmlspecialchars($columna['año_academico'] ?? '') ?></td>
                            <td>
                                <!-- Se asume que estos botones se enlazarán a formularios o scripts JS para la acción -->
                                <button class="delete_btn">Eliminar</button>  
                                <button class="edit_btn">Editar</button>  
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>