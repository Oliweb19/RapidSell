<?php
    include_once 'conexion_BD.php';
    session_start();

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rapidsell";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    //Hacer un select para saber cuantos productos 
    $sql_buscar_cantr_prod = "SELECT id_producto FROM productos WHERE estatus = 1";
    $res = mysqli_query($conexion, $sql_buscar_cantr_prod);

    
    while($mos = mysqli_fetch_array($res)){

        $productos = $mos['id_producto'];
     
            
        $sql_buscar_prod = "SELECT * FROM productos WHERE id_producto = '$productos' AND estatus = 1";
        $resu = mysqli_query($conexion, $sql_buscar_prod);
        $resu = mysqli_fetch_array($resu);
            
        $id = $resu['id_producto'];
        $precio_bs = $_POST['valor'];
        $_SESSION['dolar'] = $precio_bs;
        $new_precio = number_format($resu['precio'] * $precio_bs, 2, '.', '');

        $sql = "UPDATE productos SET precio_bs = '$new_precio' WHERE id_producto = '$id'";

        if (mysqli_query($conexion, $sql)) {
            echo "Fila modificada<br>";
        } else {
            echo "Error al modificar fila: " . mysqli_error($conexion) . "<br>";
        }

    }
    
    header("Location: mi-inventario.php");
?>