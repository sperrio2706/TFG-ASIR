<?php

ob_start();
session_start();

# Si la sesión no es válida, redirige al frontend
if (!isset($_SESSION['valid'])) {
    header('Location: https://bettercallsergio.es/index.html');
    exit; // Agrega una salida para asegurarse de que se detenga la ejecución después de la redirección
}

include 'includes/functions.php';

// Función para obtener el ID de usuario de la sesión actual
function get_userId($conexion)
{
    try {
        $consulta = "SELECT Id_Empleado FROM Empleados WHERE Email = ?";
        $resultado = $conexion->prepare($consulta);
        $email = $_SESSION["email"];
        $resultado->bindParam(1, $email);
        $resultado->execute();
        // Devuelve el ID del empleado
        return $resultado->fetchColumn();
    } catch (PDOException $e) {
        // Manejo de errores
        echo "Error al obtener el ID del empleado: " . $e->getMessage();
        return false;
    }
}

// Función para insertar la ubicación al fichar
function insert_ubi($conexion, $id_empleado)
{
    $lat = $_POST['latitude'];
    $lon = $_POST['longitude'];

    try {
        $consulta = "INSERT INTO Ubicaciones (Id_Empleado, Coordenadas) VALUES (:Id, POINT(:Lat,:Lon))";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute(array(
            ':Id' => $id_empleado,
            ':Lat' => $lat,
            ':Lon' => $lon
        ));
    } catch (PDOException $e) {
        // Manejo de errores
        echo "Error al insertar la ubicación: " . $e->getMessage();
        return false;
    }
}

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si la variable POST 'latitude' y 'longitude' están configuradas
    if (isset($_POST['latitude']) && isset($_POST['longitude'])) {

        $conexion = connectionDB();

        // Consultamos el ID de usuario de nuestra sesión
        $id_empleado = get_userId($conexion);

        if ($id_empleado != null) {

            // Insertamos la latitud y la longitud en la base de datos
            insert_ubi($conexion, $id_empleado);

            // Finalmente mostramos al usuario que el fichaje ha sido correcto
            header("Location: fichaje_correcto.html");
            exit; // Agrega una salida para asegurarse de que se detenga la ejecución después de la redirección
        } else {
            if ($_SESSION["email"] === null) {
                echo "Error: No hay un correo electrónico almacenado en la sesión.";
            } else {
                echo "Error: No se encontró ningún empleado con el correo electrónico '{$_SESSION["email"]}'.";
            }
        }
    } else {
        echo "Error: Los datos de ubicación no están configurados correctamente.";
    }
}

// Cerrar la conexión a la base de datos después de usarla
$conexion = null;
?>
