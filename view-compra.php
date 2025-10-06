<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RapidSell</title>
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="shourt icon" href="img/RapidSell mini-logo.png">
    <!-- Icons -->
	<link href="iconos/fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet">
	<link href="iconos/fontawesome-free-6.2.1-web/css/brands.css" rel="stylesheet">
	<link href="iconos/fontawesome-free-6.2.1-web/css/fontawesome.css" rel="stylesheet">
	<link href="iconos/fontawesome-free-6.2.1-web/css/regular.css" rel="stylesheet">
	<link href="iconos/fontawesome-free-6.2.1-web/css/solid.css" rel="stylesheet">
	<link href="iconos/fontawesome-free-6.2.1-web/css/svg-with-js.css" rel="stylesheet">
</head>
<body>
    <div class="menu">
        <div class="logo">
            <div class="logo-icon">
                <img src="img/RapidSell.png" alt=""> 
            </div>
            <a href="index.php"><h3>RapidSell</h3></a>
        </div>
        <div class="opciones">
            <ul>
              <li> <a href="agg-prod.php"><i class="fa-solid fa-plus"></i> Agregar Productos</a> </li>
              <li> <a href="mi-inventario.php"> <i class="fa-solid fa-bars"></i> Inventario</a></li>
              <li> <a href="venta.php"> <i class="fa-solid fa-cart-plus"></i> Hacer Venta</a></li>
              <li> <a href="mis-ventas.php"> <i class="fa-solid fa-cart-shopping"></i> Mis Ventas</a></li>
              <li> <a href="creditos.php"> <i class="fa-regular fa-id-card"></i> Creditos</a></li>
            </ul>
        </div>
        <div class="cont-btn">
            <!--<a href="index.php">Exit</a>-->
        </div>
    </div>
    <div class="cont-general">
        <div class="cont-site">
            <div class="bars-info">
                <h3>
                  <?php
                    date_default_timezone_set('America/Caracas');
                    $hora_actual = date("H:i");
                    echo $hora_actual;
                  ?>
                </h3>
                <h3>
                  <?php
                    $fecha = date("d/m/Y"); 
                    $dias = array(
                      'Monday' => 'Lunes',
                      'Tuesday' => 'Martes',
                      'Wednesday' => 'Miércoles',
                      'Thursday' => 'Jueves',
                      'Friday' => 'Viernes',
                      'Saturday' => 'Sábado',
                      'Sunday' => 'Domingo'
                    );
                    $dia_semana = $dias[date("l")];
                    echo $dia_semana . " - ";
                    echo $fecha;
                  ?>
                </h3>
            </div>
            <div class="bars-products">
                <h2>Productos de la Compra</h2>
                <table class="index-tabla">
                    <thead>
                      <tr>
                        <th>N° Factura</th>
                        <th>Productos</th>
                        <th>Precio en $</th>
                        <th>Precio en Bs</th>
                        <th>Cantidad</th>
                      </tr>
                    </thead>
                    <?php
                        include_once 'conexion_BD.php'; 

                        $fecha = date("d/m/Y");
                        $id = $_GET['id'];
  
                        $sql = "SELECT *
                        FROM ventas
                        JOIN compra ON ventas.id_compra = compra.id_compra
                        JOIN productos ON compra.id_producto = productos.id_producto
                        AND compra.id_compra = '$id'";

                        $resultado = mysqli_query($conexion,$sql);
  
                        while($mostrar = mysqli_fetch_array($resultado)){ 
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $mostrar['id_compra']?></td>
                        <td><?php echo $mostrar['nombre_producto']?></td>
                        <td><?php echo $mostrar['precio']?></td>
                        <td><?php echo $mostrar['precio_bs']?></td>
                        <td><?php echo $mostrar['cantidad_producto']?></td>
                      </tr>
                    </tbody>
                    <?php
                        $total = $mostrar['total_pagar'];
                        $total1 = $mostrar['total_pagar_bs'];
                        }
                    ?>
                    <thead>
                        <tr>
                            <th><h3>Total a Pagar en $: <?php echo $total; ?>$</h3></th>
                            <th><h3>Total a Pagar en Bs: <?php echo $total1; ?>bs</h3></th>
                        </tr>
                    </thead>
                  </table>
            </div>
        </div>
    </div>
</body>
</html>