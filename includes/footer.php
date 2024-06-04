<?php
    ob_start();
    session_start();

    if(!isset($_SESSION['valid'])){
        header('Location: https://bettercallsergio/index.html');
    }

?>


<footer>
        <div class="container">
            <p>Sergio Pérez Ríos [2024] Empresa de ejemplo</p>
        </div>
        <br><hr><br>
        <p>Bienvenido,  <?php echo $_SESSION['email'];?></p>
        <a href="logout.php" id="logout-link">Cerrar sesion</a>    
</footer>


    
