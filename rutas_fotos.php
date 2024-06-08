<?php
require('./includes/functions.php');

function removeAccents($string) {
    $tildes = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'n', 'Ñ' => 'N'
    ];
    // strtr cambia carateres con tilde almacenados en el array
    return strtr($string, $tildes);
}

function generateImagePath($nombre, $apellidos) {
    $nombre = removeAccents(strtolower(str_replace(' ', '', $nombre)));
    $apellidos = removeAccents(strtolower(str_replace(' ', '', $apellidos)));
    return "/var/www/html/images/user-images/{$nombre}{$apellidos}/perfil.jpg";
}

function actualizarInfoFacial($conexion) {
    try {
        // Consulta los datos de la tabla Empleados
        $sql = 'SELECT Id_Empleado, Nombre, Apellidos FROM Empleados';
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        // Recorre los resultados 
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_empleado = $fila['Id_Empleado'];
            $nombre = $fila['Nombre'];
            $apellidos = $fila['Apellidos'];

            // Generar la ruta de la imagen
            $ruta_imagen = generateImagePath($nombre, $apellidos);

            // Insertar en la tabla Info_Facial
            $sql_insert = 'INSERT INTO Info_Facial (Id_Empleado, Ruta) VALUES (:id_empleado, :ruta_imagen)';
            $stmt_insert = $conexion->prepare($sql_insert);
            $stmt_insert->bindParam(':id_empleado', $id_empleado);
            $stmt_insert->bindParam(':ruta_imagen', $ruta_imagen);
            $stmt_insert->execute();
        }
    } catch (PDOException $e) {
        // Manejo de errores
        echo "Error al insertar datos: " . $e->getMessage();
    }
}

$conexion = connectionDB();
actualizarInfoFacial($conexion);
?>
