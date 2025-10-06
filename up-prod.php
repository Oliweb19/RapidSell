<?php
      include_once 'conexion_BD.php';

    $id = $_POST['id'];
    $nombre = $_POST['nom'];
    $precio = $_POST['precio'];
    $precio_bs = $_POST['precio_bs'];
    $stock = $_POST['stock'];
  
    $sql = "UPDATE productos
    SET nombre_producto='$nombre', precio='$precio', precio_bs='$precio_bs', stock='$stock' 
    WHERE id_producto = '$id'"; 
    $resultado = mysqli_query($conexion,$sql);
  
    if($resultado){
        header('location:mi-inventario.php');
    }
    else{
        echo "<script> alert('No se pudo Modificar'); window.history.go(-1);</script>"; 
    } 
?>