<?php
    session_start();

    $id = $_GET['id'];
    $carrito_mio = $_SESSION['carrito'];
    
    array_splice($carrito_mio, $id, 1);
    print_r($carrito_mio);

    $_SESSION['carrito'] = $carrito_mio;
    
    header("Location: venta.php");
?>