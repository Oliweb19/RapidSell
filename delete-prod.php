<?php

    include_once 'conexion_BD.php';

    $id = $_GET['id'];

    $sql = "UPDATE productos SET estatus = 0 WHERE id_producto = '$id'"; 
	$resultado = mysqli_query($conexion,$sql);

    if($resultado){
        header('location:mi-inventario.php');
    }
    else{
        echo "<script> alert('No se pudo eliminar'); window.history.go(-1);</script>"; 
    } 

?>