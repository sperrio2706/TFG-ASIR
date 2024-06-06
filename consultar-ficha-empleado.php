<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi empresa</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .table-container {
            width: 80%;
            margin: 20px auto;
            overflow-x: auto;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        /* Estilos de la tabla */
        .consulta-fichaje {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
        }

        .consulta-fichaje thead {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }

        .consulta-fichaje th,
        .consulta-fichaje td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            color: black;
            text-align: center;
        }

        .consulta-fichaje tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .consulta-fichaje tbody tr:hover {
            background-color: #f1f1f1;
        }

        .consulta-fichaje th {
            background-color: blue;
            color: black;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="images/logo.png" alt="Logo de la empresa">
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="fichar.html">Fichar</a></li>
                    <li><a href="admin-panel.html">Panel de administracion</a></li>
                    <li><a href="contacto.html">Contacto</a></li>
                </ul>
            </nav>
            <div class="employee-area">
                <a href="profile.php">Perfil del empleado</a>
            </div>
        </div>
    </header>

    <section class="main-content">
        <div class="container">
            <h2>Lista de empleados </h2>
            <p>Página de empresa para TFG de ASIR</p>
        </div>
    </section>


    <div class="table-container">
        <table class="consulta-fichaje">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Jefe</th>
                    <th>Ver perfil</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('./includes/functions.php');

                function insertar_fila($nombre, $apellidos, $email, $jefe)
                {
                    

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($nombre) . '</td>';
                    echo '<td>' . htmlspecialchars($apellidos) . '</td>';
                    echo '<td>' . htmlspecialchars($email) . '</td>';
                    echo '<td>' . htmlspecialchars($jefe) . '</td>';
                    echo '<td>';
                    echo '<form action="get_user_info.php" method="post">';
                    echo '<input type="hidden" name="email" value="' . htmlspecialchars($email) . '">';
                    echo '<button type="submit">Pulsa aquí</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }

                function obtener_datos_empleado($conexion)
                {
                    $sql = "
        SELECT E.Nombre, E.Apellidos, E.Email, E.Jefe
        FROM Empleados E
        GROUP BY E.Id_Empleado, E.Nombre, E.Apellidos, E.Email, E.Jefe;
    ";

                    $stmt = $conexion->prepare($sql);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $resultado;
                }

                $conexion = connectionDB();
                $datos_empleado = obtener_datos_empleado($conexion);

                foreach ($datos_empleado as $datos) {
                    insertar_fila($datos['Nombre'], $datos['Apellidos'], $datos['Email'], $datos['Jefe']);
                }
                ?>


            </tbody>
        </table>
    </div>




    <footer>
        <div class="container">
            <p>Sergio Pérez Ríos [2024] Empresa de ejemplo</p>
        </div>
    </footer>
</body>

</html>