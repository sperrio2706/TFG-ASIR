<?php
session_start();
require_once('../includes/functions.php');

if (isset($_SESSION['email'])) {
    //$email = $_SESSION['email'];
    $email = $_SESSION['email'];
    $connection = connectionDB();

    $sql = "SELECT Nombre, Apellidos FROM Empleados WHERE Email = :email";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $name = $user['Nombre'];
        $surname = $user['Apellidos'];
        $ruta = generateImagePath($name, $surname);
        $ruta_sin_barras = str_replace('\\', '', $ruta);

        // Crear el array asociativo con la ruta de la imagen
        $response = ['user_image_path' => $ruta_sin_barras];

        // Enviar el JSON como respuesta
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} else {
    echo json_encode(['error' => 'Session not set']);
}
?>
