<?php
    session_start();
    unset($_SESSION['email']);
    unset($_SESSION['valid']);
    session_destroy();

    header('Location: https://bettercallsergio.es/index.html');


?>