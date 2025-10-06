<?php
    date_default_timezone_set('America/Caracas');

    session_start();

    include_once 'conexion_BD.php';

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "rapidsell";

    $conn = new mysqli($servername, $username, $password, $dbname); 
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Array de datos a insertar
    $carrito = $_SESSION['carrito'];
    
    do{
        //Creacion de Numero de factura
        $_SESSION['ref'] = mt_rand(1,999999999).mt_rand(1,999999999);
        $codigo = $_SESSION['ref'];
        
        //Validar que el numero de factura no exista
        $sql_validar = "SELECT id_compra FROM ventas WHERE id_compra = '$codigo'";
        $resultado = mysqli_query($conexion, $sql_validar);
        $ver = mysqli_num_rows($resultado);
        
        $_SESSION['ref'] = $codigo;

        if($ver == 0){
            $fecha = date("d/m/Y"); 
            
            //Recibir variables
            $nom = $_POST['nombre'];
            $metodo_pago = $_POST['mdp'];
            $total = $_POST['total'];
            $total1 = $_POST['total1'];


            // Recorrer el array y ejecutar las inserciones en compra
            for ($i = 0; $i < count($carrito); $i++) { 
                
                $id = $carrito[$i]['id'];
                $cant = $carrito[$i]['cantidad'];
                $fac = $_SESSION['ref'];
                //var_dump($fac);
                
                $sql = "INSERT INTO compra(id_compra, id_producto, fecha_compra, nombre, cantidad_producto) 
                VALUES ('$fac','$id','$fecha','$nom','$cant')";
                if ($conn->query($sql) === TRUE) {
                    echo "Fila insertada correctamente<br>";
                } else {
                    echo "Error al insertar fila: " . $conn->error . "<br>";
                }
            }

            // Insertar la campra en la tabla en ventas
            $fac = $_SESSION['ref'];

            $sql_agregar = "INSERT INTO ventas(id_compra, total_pagar, total_pagar_bs, metodo_pago, fecha) 
            VALUES ('$fac','$total','$total1','$metodo_pago','$fecha')";
            $resultado = mysqli_query($conexion, $sql_agregar);
            
            if($resultado){
                echo "Fila insertada correctamente<br>";
            }
            else{
                echo "Error al insertar";
            }

            // Insertar la campra en la tabla en credito
            if($metodo_pago == "Credito"){
                $sql_buscar = "SELECT id_ventas FROM ventas WHERE id_compra = '$fac'";
                $res = mysqli_query($conexion, $sql_buscar);
                $res = mysqli_fetch_array($res);

                $id_ventas = $res['id_ventas'];

                $sql_credito = "INSERT INTO credito(id_venta, estatu) VALUES ('$id_ventas','1')";
                $resultado = mysqli_query($conexion, $sql_credito);
            }

            //Modificar el stcok
            for ($i = 0; $i < count($carrito); $i++) {
                
                $id = $carrito[$i]['id'];
                $cant = $carrito[$i]['cantidad'];

                $sql_buscar = "SELECT stock FROM productos WHERE id_producto = $id";
                $res = mysqli_query($conexion, $sql_buscar);
                $res = mysqli_fetch_array($res);
                
                $new_stock = $res['stock'] - $cant;

                $sql = "UPDATE productos SET stock='$new_stock' WHERE id_producto = '$id'";

                if ($conn->query($sql) === TRUE) {
                    echo "Fila modificada<br>";
                } else {
                    echo "Error al modificar fila: " . $conn->error . "<br>";
                }
            }
        }
        else{
            echo "<script> window.alert('Lo siento ya no se puede hacer más ventas, contacta al creador. FALLA: EL NUMERO DE FACTURA LLEGO A SU LIMITE'); window.location='view-cart.php';</script>";
        }
    }while($ver > 0);
    // Cerrar la conexión a la base de datos
    $conn->close();
    header("Location: borrarcarro.php");
?>