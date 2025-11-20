<?php

require_once './../db/conexion.php';


try {
    // ...existing code...
    $resultados = [];
    $consulta = null;
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

    if (isset($_GET['query'])) {
        $busqueda = $_GET['query'];
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
    $consulta_total = $conn->query("SELECT COUNT(*) FROM usuarios INNER JOIN roles ON usuarios.rol_id = roles.id WHERE roles.nombre = 'alumno'");
    $registros_totales = $consulta_total->fetchColumn();
} catch (PDOException $e) {
    echo "<div style='color:red;'>¡Error en la consulta!<br>" . $e->getMessage() . "</div>";
}

?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/style.css" type="text/css">
    <title>CRUD ALUMNES</title>
</head>
<header>
    <img src="./img/logoextendido.png">
</header>
<body class="CRUD">
    <br><br><br><br>
    <div class="contenedor">
        <br>
        <div class="create-button">
            <nav>
                <div>
                    <form class="d-flex" role="search" method="GET" action="">
                        <input class="form-control me-2" type="search" name="query" placeholder="Buscar" aria-label="Buscar">
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                        <a class="button_c" href="./crud.php">Eliminar</a>
                    </form>
                </div>
            </nav>

        </div>
        <div class="cambiar-añadir">
            <div class='image-container' id="cruz">
                <a href="./formularios/alumne/formcrearAlumne.php">
                    <img src='./img/square-plus-solid.png' alt='Imagen Default' class='image image-default'>
                    <img src='./img/square-plus-solid-green.png' alt='Imagen Default' class='image image-hover'>
                </a>
            </div>
            <a class="button_c" name="profes" href="./crud_profes.php">Cambiar a profes</a>
            <br><br>
            <?php

                if (isset($_GET['query'])) {
                    echo "<h3>Total de registros: $total_registros_filtrados</h3>";
                } else {
                    echo "<h3>Total de registros:  $registros_totales</h3>";
                }

            ?>
        </div>
    </div>
    <div class="container">
        <table>
            <form action="" method="post">
                <thead class="thead">
                    <tr>
                        <th><input type="submit" value="⬆" id="flecha_izquierda" name="usuario_asc">Usuario<input type="submit" value="⬇" id="flecha_derecha" name="usuario_desc"></th>
                        <th><input type="submit" value="⬆" id="flecha_izquierda" name="nombre_asc">Nombre<input type="submit" value="⬇" id="flecha_derecha" name="nombre_desc"></th>
                        <th>Apellidos</th>
                        <th><input type="submit" value="⬆" id="flecha_izquierda" name="email_asc">Email<input type="submit" value="⬇" id="flecha_derecha" name="email_desc"></th>
                        <th><input type="submit" value="⬆" id="flecha_izquierda" name="grupo_asc">Grupo<input type="submit" value="⬇" id="flecha_derecha" name="grupo_desc"></th>
                        <th><input type="submit" value="⬆" id="flecha_izquierda" name="año_asc">Año<input type="submit" value="⬇" id="flecha_derecha" name="año_desc"></th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </form>
            <tbody>
                <?php foreach ($resultados as $columna): ?>
                    <tr>
                        <td ><?= htmlspecialchars($columna['usuario']) ?></td>
                        <td ><?= htmlspecialchars($columna['nombre']) ?></td>
                        <td ><?= htmlspecialchars($columna['apellidos']) ?></td>
                        <td ><?= htmlspecialchars($columna['email']) ?></td>
                        <td ><?= htmlspecialchars($columna['grupo'] ?? '') ?></td>
                        <td ><?= htmlspecialchars($columna['año_academico'] ?? '') ?></td>
                        <td>
                            <button>Eliminar</button>  
                            <button>Editar</button>  
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>
</body>
