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
            <h2>Consulta de fichajes </h2>
            <p>Página de empresa para TFG de ASIR</p>
        </div>
    </section>


    <div class="table-container">
        <table class="consulta-fichaje">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Nº empleados fichados</th>
                    <th>Día de la semana</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('./includes/functions.php');

                function insertar_fila($fecha, $num_empleados, $dia_semana)
                {
                    echo '<form action="consultar_fichajes_procesador.php" method="post">';
                    echo '<input type="hidden" name="fecha" value="' . $fecha . '">';
                    echo '<tr><td><button type="submit">' . $fecha . '</button></td><td>' . $num_empleados . '</td><td>' . $dia_semana . '</td></tr>';
                    echo '</form>';
                }

                function obtenerFechasDistintas($conexion)
                {
                    try {
                        // Consulta las fechas distintas
                        $stmt = $conexion->prepare('SELECT DISTINCT DATE(Fecha) AS Fecha FROM Ubicaciones');
                        $stmt->execute();

                        // Inicializa un array para almacenar los resultados
                        $fechas = [];

                        $tuplas = $stmt->fetchAll();

                        $contador = 0;
                        // Recorre los resultados y los almacena en el array
                        foreach ($tuplas as $tupla) {
                            $fechas[$contador] = $tupla['Fecha'];
                            $contador++;
                        }

                        // Devuelve el array de fechas
                        return $fechas;
                    } catch (PDOException $e) {
                        // Manejo de errores
                        echo "Error al obtener fechas distintas: " . $e->getMessage();
                        return false; // Otra opción: lanzar una nueva excepción o devolver un valor específico según el caso
                    }
                }


                // Función que obtiene el numero de empleados que ficharon un dia concreto

                function obtener_numero_empleados($conexion, $fecha)
                {
                    // Consulta las fechas distintas
                    $sql = "SELECT COUNT(DISTINCT E.Id_Empleado) AS Num_Empleados
                FROM Empleados E
                INNER JOIN Ubicaciones U ON E.Id_Empleado = U.Id_Empleado
                WHERE DATE(U.Fecha) = :fecha";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bindParam(':fecha', $fecha);
                    $stmt->execute();

                    // Devuelve el array de fechas
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    return $result['Num_Empleados'];
                }

                $conexion = connectionDB();


                // Sacamos todas las fechas sin repetir en que han habido fichajes
                $fechas = obtenerFechasDistintas($conexion);


                // Recorre las fechas y llama a la función insertar_fila
                foreach ($fechas as $fecha) {
                    // Aquí puedes calcular $num_empleados y $dia_semana si es necesario
                    $num_empleados = obtener_numero_empleados($conexion, $fecha);
                    $dia_semana = date('1', strtotime($fecha));

                    insertar_fila($fecha, $num_empleados, $dia_semana);
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