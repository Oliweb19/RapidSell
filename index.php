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
    <!-- Graficas -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']}); 
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() { 

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          <?php 
            $sql = "SELECT SUM(compra.cantidad_producto) AS total_ventas, productos.nombre_producto 
            FROM compra 
            JOIN productos 
            WHERE compra.id_producto = productos.id_producto 
            GROUP BY compra.id_producto 
            ORDER BY total_ventas DESC
            LIMIT 5";
            $resultado = mysqli_query($conexion,$sql);

            while($mostrar = mysqli_fetch_array($resultado)){
              echo "['".$mostrar["nombre_producto"]."', ".$mostrar["total_ventas"]."],";
            }
          ?>
        ]);

        var options = {
          title: 'Productos más Vendidos'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options); 
      }
    </script>
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
            <div class="cont-bars">
                <div class="bars-products">
                    <h2>Productos que estan por acabarse</h2> 
                    <table class="index-tabla">
                        <thead>
                          <tr>
                            <th>Codigo de Barra</th>
                            <th>Producto</th>
                            <th>Stock</th>
                          </tr>
                        </thead>
                        <?php 
                          $sql = "SELECT * FROM productos WHERE stock <= 5 AND estatus = 1";
                          $resultado = mysqli_query($conexion,$sql);

                          while($mostrar = mysqli_fetch_array($resultado)){ 
                        ?>
                        <tbody>
                          <tr>
                            <td><?php echo $mostrar['cerial']; ?></td>
                            <td><?php echo $mostrar['nombre_producto']; ?></td>
                            <td><?php echo $mostrar['stock']; ?></td>
                          </tr>
                        </tbody>
                        <?php
                          }
                        ?>
                      </table>
                </div>
                <div class="cont-2div">
                    <div class="bars-comprador">
                        <div id="piechart"></div>
                    </div>
                    <div class="bars-monto">
                      <div class="bars-products">
                        <h2>Buscar Montos</h2>
                        <form action="index.php" method="post" class="monto">
                          <input type="search" name="date" id="" placeholder="Ejemplo. 00/00/0000" class="especial">
                          <button type="submit" id="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                        <?php
                          //var_dump($_POST['date']);
                          if(isset($_POST['date']) == NULL){ 
                        ?>    
                      </div>
                      <div class="bars-products">
                        <h2>Montos Diarios</h2>
                        <table class="index-tabla">
                            <thead>
                              <tr>
                                <th>Metodo de pago</th> 
                                <th>Total</th>
                              </tr>
                            </thead>
                            <?php 
                              $sql = "SELECT SUM(total_pagar), metodo_pago, fecha FROM ventas 
                              WHERE fecha = '$fecha' 
                              GROUP BY ventas.metodo_pago
                              ORDER BY metodo_pago ASC";
                              $resultado = mysqli_query($conexion,$sql);

                              while($mostrar = mysqli_fetch_array($resultado)){ 
                            ?>
                            <tbody>
                              <tr>
                                <td><?php echo $mostrar['metodo_pago']; ?></td>
                                <td><?php echo $mostrar['SUM(total_pagar)']; ?>$</td>
                              </tr> 
                            </tbody>
                            <?php
                              }
                            ?>
                            <tfoot>
                            <?php
                              // Sumar el total de todos los métodos de pago en dólares
                              $sql_total = "SELECT SUM(total_pagar) AS total_dolares FROM ventas WHERE fecha = '$fecha'";
                              $resultado_total = mysqli_query($conexion, $sql_total);
                              $row_total = mysqli_fetch_assoc($resultado_total);
                              $total_dolares = $row_total['total_dolares'];
                            ?>
                            <tr>
                              <td colspan="1" class="cart-total"><h3>Total: <?php echo number_format($total_dolares, 3); ?>$</h3></td>
                              <!-- ...Limite el numero de decimales a 3... -->
                            </tr>
                            </tfoot>
                          </table>
                      </div>
                      <?php
                        }
                        else{
                      ?>
                      <div class="bars-products">
                        <h2>Montos Diarios</h2>
                        <table>
                            <thead>
                              <tr>
                                <th>Metodo de pago</th> 
                                <th>Total</th>
                              </tr>
                            </thead>
                            <?php 
                              $date = $_POST['date'];

                              $sql = "SELECT SUM(total_pagar), metodo_pago, fecha FROM ventas 
                              WHERE fecha LIKE '%$date%' 
                              GROUP BY ventas.metodo_pago
                              ORDER BY metodo_pago ASC";
                              $resultado = mysqli_query($conexion,$sql);

                              while($mostrar = mysqli_fetch_array($resultado)){ 
                            ?>
                            <tbody>
                              <tr>
                                <td><?php echo $mostrar['metodo_pago']; ?></td>
                                <td><?php echo $mostrar['SUM(total_pagar)']; ?>$</td>
                              </tr>
                            </tbody>
                            <?php
                              }
                            ?>
                            <tfoot>
                            <?php
                              // Sumar el total de todos los métodos de pago en dólares
                              $sql_total = "SELECT SUM(total_pagar) AS total_dolares FROM ventas WHERE fecha LIKE '%$date%'";
                              $resultado_total = mysqli_query($conexion, $sql_total);
                              $row_total = mysqli_fetch_assoc($resultado_total);
                              $total_dolares = $row_total['total_dolares'];
                            ?>
                            <tr>
                              <td colspan="1" class="cart-total"><h3>Total: <?php echo number_format($total_dolares, 3); ?>$</h3></td>
                              <!-- ...Limite el numero de decimales a 3... -->
                            </tr>
                            </tfoot>
                          </table>
                      </div>
                      <?php
                        }
                      ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>