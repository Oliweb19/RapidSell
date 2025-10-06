<?php
    include_once 'conexion_BD.php';

    $cerial = $_POST['cerial'];
    $nombre = $_POST['nom'];
    $unidades = $_POST['unidades'];
    $bultos = $_POST['bultos'];
    $precio = $_POST['precio'];
    $porcentaje = $_POST['stock'];
    $stock = $unidades * $bultos;

    // Validar que el cerial no exista
    $sql_validar = "SELECT id_producto FROM productos WHERE cerial = '$cerial' AND estatus = 1";
    $res_validar = mysqli_query($conexion, $sql_validar);

    if(mysqli_num_rows($res_validar) > 0){
        echo "<script> alert('El código de barra ya existe.'); window.history.go(-1);</script>";
        exit;
    }

    // Datos del problema
    $costo_bulto = $precio;
    $porcentaje_ganancia = ($porcentaje / 100); 


    // Calcular el costo por unidad
    $costo_unidad = $costo_bulto / $unidades;


    // Calcular el precio de venta por unidad con el 30% de ganancia
    $precio_venta_unidad = number_format($costo_unidad / (1 - $porcentaje_ganancia), 2, '.', '');

    // Calcular la ganancia por unidad
    $ganancia_unidad = $precio_venta_unidad - $costo_unidad;

    // Calcular la ganancia total
    $ganancia_total = $ganancia_unidad * $unidades;
    $ganancia = number_format($ganancia_total * $bultos, 2, '.', '');
 


    $sql_agregar = "INSERT INTO productos(cerial, nombre_producto, precio, precio_bs, ganancia,stock) 
    VALUES ('$cerial','$nombre','$precio_venta_unidad', 0, '$ganancia','$stock')";
    $resultado = mysqli_query($conexion, $sql_agregar);
    
    if($resultado){
        echo "<script> alert('Producto Guardado'); window.location='mi-inventario.php';</script>";
    }
    else{
        echo "<script> alert('Fallo al Guardar'); window.history.go(-1);</script>";
    }
?>