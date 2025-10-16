<?php 
    session_start(); 
    header("Location: precio_unitario.php");
    unset($_SESSION['carrito']);
    unset($_SESSION['ref']);
?>