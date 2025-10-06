<?php 
    session_start(); 
    header("Location: venta.php");
    unset($_SESSION['carrito']);
    unset($_SESSION['ref']);
?>