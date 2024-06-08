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

        .table-container {
            width: 80%;
            margin: 20px auto;
            overflow-x: auto;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }

        /* Estilo para la imagen dentro de la tabla */
        .consulta-fichaje img {
            max-width: 50%;
            height: auto;
            display: block;
            margin: auto;
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
            <h2>Perfil detallado</h2>
            <p>Página de empresa para TFG de ASIR</p>
        </div>
    </section>


    <div class="table-container">
        <table class="consulta-fichaje">
            <thead>
                <tr>
                    <th>Foto de perfil</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Jefe</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('./includes/functions.php');

                function insertar_fila($ruta_foto, $nombre, $apellidos, $email, $jefe)
                {

                    // Transformamos la ruta absoluta a relativa
                    $ruta_relativa  = str_replace("/var/www/html",".",$ruta_foto);

                    echo '<tr>';
                    echo '<td>';
                    echo '<img src="' . $ruta_relativa . '">';
                    echo '</td>';
                    echo '<td>' . htmlspecialchars($nombre) . '</td>';
                    echo '<td>' . htmlspecialchars($apellidos) . '</td>';
                    echo '<td>' . htmlspecialchars($email) . '</td>';
                    echo '<td>' . htmlspecialchars($jefe) . '</td>';
                    echo '</tr>';
                }

                function obtener_datos_empleado($conexion)
                {
                    $sql = "
                    SELECT E.Id_Empleado, E.Nombre, E.Apellidos, E.Email, E.Jefe
                    FROM Empleados E
                    WHERE E.Email=:email
                    GROUP BY E.Id_Empleado, E.Nombre, E.Apellidos, E.Email, E.Jefe;
                    ";

                    $stmt = $conexion->prepare($sql);
                    $stmt->bindParam(':email', $_POST['email']);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $resultado;
                }


                function obtenerRutaImagen($conexion, $id)
                {
                    try {
                        // Consulta la ruta de la imagen para el ID dado
                        $sql = 'SELECT Ruta FROM Info_Facial WHERE Id_Empleado = :id';
                        $stmt = $conexion->prepare($sql);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();

                        // Obtiene la ruta (si existe)
                        $ruta = $stmt->fetchColumn();

                        // Devuelve la ruta
                        return $ruta;
                    } catch (PDOException $e) {
                        // Manejo de errores
                        echo "Error al consultar la ruta de la imagen: " . $e->getMessage();
                        return null; // Devuelve null en caso de error
                    }
                }

                $conexion = connectionDB();
                $datos_empleado = obtener_datos_empleado($conexion);

                foreach ($datos_empleado as $datos) {
                    // asignamos la ruta de la foto de perfil
                    $ruta_foto = obtenerRutaImagen($conexion, $datos["Id_Empleado"]);
                    insertar_fila($ruta_foto, $datos['Nombre'], $datos['Apellidos'], $datos['Email'], $datos['Jefe']);
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