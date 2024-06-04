<?php
    ob_start();
    session_start();

    # Si la sesión no es valida me redirige al frontend
    if(!isset($_SESSION['valid'])){
        header('Location: https://bettercallsergio.es/index.html');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi empresa</title>
    <link rel="stylesheet" href="styles.css">
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
                    <li><a href="contacto.html">Contacto</a></li>
                </ul>
            </nav>
            <div class="employee-area">
            <p>Bienvenido/a <?php echo $_SESSION['email'];?> !</p>
            <a href="logout.php" id="logout-link">Cerrar sesion</a>
            </div>
        </div>
    </header>


