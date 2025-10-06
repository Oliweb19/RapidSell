<?php
  include_once 'conexion_BD.php';
?>
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
                  <h2>Buscar ventas por la fecha</h2>
                  <form action="mis-ventas.php" method="post" class="monto"> 
                    <input type="search" name="date" id="" placeholder="Ejemplo. 00/00/0000" class="especial">
                    <button type="submit" id="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
                  </form>
                  <?php
                    //var_dump($_POST['date']);
                    if(isset($_POST['date']) == NULL){ 
                  ?>    
            </div>
            <div class="bars-products">
                <h2>Mis Ventas</h2>
                <table class="index-tabla">
                    <thead>
                      <tr>
                        <th>N° Factura</th>
                        <th>Total en $</th>
                        <th>Total en Bs</th>
                        <th>Metodo de Pago</th>
                        <th>Fecha de Venta</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <?php 
                      //$fecha = date("d/m/Y");
                      
                      $sql = "SELECT * FROM ventas WHERE fecha='$fecha'";
                      $resultado = mysqli_query($conexion,$sql);

                      while($mostrar = mysqli_fetch_array($resultado)){ 
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $mostrar['id_compra']; ?></td>
                        <td><?php echo $mostrar['total_pagar']; ?>$</td>
                        <td>Bs. <?php echo $mostrar['total_pagar_bs']; ?></td>
                        <td><?php echo $mostrar['metodo_pago']; ?></td>
                        <td><?php echo $mostrar['fecha']; ?></td>
                        <td>
                            <a href="view-compra.php?id=<?php echo $mostrar['id_compra']; ?>" id="view"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>
                    </tbody>
                    <?php
                      }
                    ?>
                  </table>
            </div>
            <?php
              }
              else{
            ?>
            <div class="bars-products">
                <h2>Mis Ventas</h2>
                <table>
                    <thead>
                      <tr>
                        <th>N° Factura</th>
                        <th>Total en $</th>
                        <th>Total en Bs</th>
                        <th>Metodo de Pago</th>
                        <th>Fecha de Venta</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <?php 
                      $fecha = $_POST['date'];
                      
                      $sql = "SELECT * FROM ventas WHERE fecha='$fecha'";
                      $resultado = mysqli_query($conexion,$sql);

                      while($mostrar = mysqli_fetch_array($resultado)){ 
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $mostrar['id_compra']; ?></td>
                        <td><?php echo $mostrar['total_pagar']; ?>$</td>
                        <td>Bs. <?php echo $mostrar['total_pagar_bs']; ?></td>
                        <td><?php echo $mostrar['metodo_pago']; ?></td> 
                        <td><?php echo $mostrar['fecha']; ?></td>
                        <td>
                            <a href="view-compra.php?id=<?php echo $mostrar['id_compra']; ?>" id="view"><i class="fa-regular fa-eye"></i></a>
                        </td>
                      </tr>
                    </tbody>
                    <?php
                      }
                    ?>
                  </table>
            </div>
            <?php
              }
            ?>
        </div>
    </div>
</body>
</html>