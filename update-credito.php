<?php
    include_once 'conexion_BD.php';

    $id = $_GET['id'];

    $sql = "UPDATE credito SET estatu='0' WHERE id_credito = '$id'";
    $resultado = mysqli_query($conexion,$sql);

    header('location:creditos.php');
?>