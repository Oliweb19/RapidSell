<?php
    include_once 'conexion_BD.php';

    $id = $_POST['id'];
    $cerial = $_POST['cerial'];
    $nombre = $_POST['nom'];
    $unidades = $_POST['unidades'];
    $bultos = $_POST['bultos'];
    $precio = $_POST['precio'];
    $porcentaje = $_POST['porcentaje'];
    $stock = $unidades * $bultos;

    // Calcular el costo por unidad
    $costo_bulto = floatval($precio);
    $porcentaje_ganancia = ($porcentaje / 100);
    $costo_unidad = $costo_bulto / $unidades;
    $precio_venta_unidad = number_format($costo_unidad / (1 - $porcentaje_ganancia), 2, '.', '');
    $ganancia_unidad = $precio_venta_unidad - $costo_unidad;
    $ganancia_total = $ganancia_unidad * $unidades;
    $ganancia = number_format($ganancia_total * $bultos, 2, '.', '');

    $sql = "UPDATE productos
    SET cerial='$cerial', nombre_producto='$nombre', precio='$precio_venta_unidad', ganancia='$ganancia', stock='$stock' 
    WHERE id_producto = '$id'";
    $resultado = mysqli_query($conexion,$sql);

    if($resultado){
        header('location:mi-inventario.php');
    }
    else{
        echo "<script> alert('No se pudo Modificar'); window.history.go(-1);</script>"; 
    }
?>