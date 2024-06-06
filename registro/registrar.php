<?php
    require_once('../includes/Register.class.php');
    if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['password'])){
        $register = new Register($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['password'], $_POST['is_boss']);
        $resultado = $register->get_confirmation();
    } else {
        echo "<h1>ERROR</h1>";
        echo "<h3>La solicitud POST no ha sido enviada correctamente</h3>";

    }

    # una vez registrado redirigimos a registro correcto.html
    if ($resultado){
        header('Location: https://bettercallsergio.es:8080/registro/registro_correcto.html');
    } else {
        echo "<h1>ERROR</h1>";
        echo "<h3>El usuario ya existe en el sistema</h3>";
    }

?>


