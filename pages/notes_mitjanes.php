<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Notes Mitjanes</title>
    <link rel="stylesheet" href="../styles/styles.css">

    <style>
        /* --- ESTILOS EXTRA MÍNIMOS --- */

        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: #f0f0f0;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            font-size: 0.95rem;
        }

        th {
            font-weight: 600;
            color: #333;
        }

        .section-divider {
            margin: 30px 0;
            border: none;
            height: 1px;
            background: #ddd;
        }

        .highlight-text {
            font-weight: 600;
            color: #004791;
            font-size: 1.1rem;
        }
    </style>
</head>

<body id="home_body">

<header class="main_header">
    <img src="../img/logo.png" class="logo_img" alt="Logo">
    <div class="user_info">
        <span class="username">Administrador</span>
    </div>
    <a href="../index.php" class="logout_btn" style="text-decoration: none;">Tancar Sessió</a>
</header>

<main>
    <div class="admin-content">

        <h2 class="form-title">Estadístiques de Notes</h2>

        <div class="user-edit-form">

            <?php
            require_once '../db/conexion.php';

            try {
                // 1. Mitjanes per Assignatura
                $sql_avg_subj = "SELECT a.nombre, AVG(n.calificacion) as mitjana 
                                 FROM notas n 
                                 JOIN asignaturas a ON n.asignatura_id = a.id 
                                 GROUP BY a.id, a.nombre 
                                 ORDER BY mitjana DESC";
                $stmt_avg_subj = $conn->query($sql_avg_subj);
                $avg_subjects = $stmt_avg_subj->fetchAll(PDO::FETCH_ASSOC);

                // Calcular la millor assignatura
                $best_subject = null;
                if (!empty($avg_subjects)) {
                    $best_subject = $avg_subjects[0]; // Com que està ordenat DESC, la primera és la millor
                }

                // 2. Top 3 Alumnes (Mitjana Global) - Mantingut per sol·licitud prèvia
                $sql_top3 = "SELECT u.nombre, u.apellidos, AVG(n.calificacion) as mitjana 
                             FROM notas n 
                             JOIN matriculas m ON n.matricula_id = m.id 
                             JOIN usuarios u ON m.usuario_id = u.id 
                             GROUP BY u.id, u.nombre, u.apellidos 
                             ORDER BY mitjana DESC 
                             LIMIT 3";
                $stmt_top3 = $conn->query($sql_top3);
                $top3_students = $stmt_top3->fetchAll(PDO::FETCH_ASSOC);

                // 3. Millors Alumnes per Assignatura (Unic per assignatura)
                // Requeriment: "si 2 alumnes han tret la millor nota en una assignatura només ens quedem amb el primer"
                $sql_best_per_subj = "SELECT a.nombre as asignatura, u.nombre, u.apellidos, n.calificacion 
                                      FROM notas n 
                                      JOIN asignaturas a ON n.asignatura_id = a.id 
                                      JOIN matriculas m ON n.matricula_id = m.id 
                                      JOIN usuarios u ON m.usuario_id = u.id 
                                      WHERE n.id = (
                                          SELECT n2.id 
                                          FROM notas n2 
                                          WHERE n2.asignatura_id = n.asignatura_id 
                                          ORDER BY n2.calificacion DESC, n2.id ASC 
                                          LIMIT 1
                                      ) 
                                      ORDER BY a.nombre";
                $stmt_best_per_subj = $conn->query($sql_best_per_subj);
                $best_per_subj = $stmt_best_per_subj->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                echo "<p style='color:red'>Error al carregar dades: " . $e->getMessage() . "</p>";
            }
            ?>

            <!-- MITJANES PER ASSIGNATURA -->
            <div class="form-group-main full-width">
                <h3>Mitjanes per Assignatura</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Assignatura</th>
                                <th>Nota Mitjana</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($avg_subjects)): ?>
                                <?php foreach ($avg_subjects as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                                        <td><?= number_format($row['mitjana'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2">No hi ha dades disponibles.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="section-divider">

            <!-- MILLOR MITJANA -->
            <div class="form-group-main full-width">
                <h3>Assignatura amb la Mitjana Més Alta</h3>
                <?php if ($best_subject): ?>
                    <p class="highlight-text">
                        <?= htmlspecialchars($best_subject['nombre']) ?> 
                        (<?= number_format($best_subject['mitjana'], 2) ?>)
                    </p>
                <?php else: ?>
                    <p>No hi ha dades.</p>
                <?php endif; ?>
            </div>

            <hr class="section-divider">

            <!-- TOP 3 ALUMNES -->
            <div class="form-group-main full-width">
                <h3>Top 3 Alumnes (Mitjana Global)</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Posició</th>
                                <th>Alumne</th>
                                <th>Nota Mitjana Global</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($top3_students)): ?>
                                <?php $pos = 1; foreach ($top3_students as $student): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            if ($pos == 1) echo "🥇";
                                            elseif ($pos == 2) echo "🥈";
                                            elseif ($pos == 3) echo "🥉";
                                            else echo $pos;
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($student['nombre'] . ' ' . $student['apellidos']) ?></td>
                                        <td><strong><?= number_format($student['mitjana'], 2) ?></strong></td>
                                    </tr>
                                <?php $pos++; endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3">No hi ha dades d'alumnes.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="section-divider">

            <!-- MILLORS ALUMNES PER ASSIGNATURA -->
            <div class="form-group-main full-width">
                <h3>Millors Alumnes per Assignatura</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Assignatura</th>
                                <th>Alumne</th>
                                <th>Nota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($best_per_subj)): ?>
                                <?php foreach ($best_per_subj as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['asignatura']) ?></td>
                                        <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellidos']) ?></td>
                                        <td><?= number_format($row['calificacion'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3">No hi ha dades.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>
</body>
</html>
