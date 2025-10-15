<?php
  session_start();
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
    <link rel="stylesheet" href="css/modal.css">
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
                              $ids_pago = [
                                'Efectivo' => 'totalEfectivo',
                                'Pago Móvil' => 'totalPagoMovil',
                                'pago_movil' => 'totalPagoMovil',
                                'efectivo' => 'totalEfectivo',
                                'Punto' => 'totalPunto',
                                'Credito' => 'totalCredito'
                              ];
                              while($mostrar = mysqli_fetch_array($resultado)){ 
                                $metodo = $mostrar['metodo_pago'];
                                $id_td = isset($ids_pago[$metodo]) ? $ids_pago[$metodo] : '';
                            ?>
                            <tbody>
                              <tr>
                                <td><?php echo $metodo; ?></td>
                                <?php $valor_row = isset($mostrar['SUM(total_pagar)']) ? floatval($mostrar['SUM(total_pagar)']) : 0; ?>
                                <td><span <?php if($id_td) echo 'id="'.$id_td.'"'; ?>><?php echo str_replace('.', ',', number_format($valor_row, 2)); ?></span>$</td>
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
                              <?php $total_dolares_val = isset($row_total['total_dolares']) ? floatval($row_total['total_dolares']) : 0; ?>
                              <td colspan="1" class="cart-total"><h3>Total: <?php echo str_replace('.', ',', number_format($total_dolares_val, 2)); ?>$</h3></td>
                              <!-- ...Limite el numero de decimales a 2... -->
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
                                <?php $valor_row = isset($mostrar['SUM(total_pagar)']) ? floatval($mostrar['SUM(total_pagar)']) : 0; ?>
                                <td><?php echo str_replace('.', ',', number_format($valor_row, 2)); ?>$</td>
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
                              $total_dolares = number_format($row_total['total_dolares'], 2);
                            ?>
                            <tr>
                              <?php $total_dolares_val = isset($row_total['total_dolares']) ? floatval($row_total['total_dolares']) : 0; ?>
                              <td colspan="1" class="cart-total"><h3>Total: <?php echo str_replace('.', ',', number_format($total_dolares_val, 2)); ?>$</h3></td>
                              <!-- ...Limite el numero de decimales a 2... -->
                            </tr>
                            </tfoot>
                          </table>
                      </div>
                      <?php
                        }
                      ?>
                      <button type="button" id="btnAvance">Hacer Avances <i class="fa-solid fa-money-bill-1-wave"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Modal Avance -->
<div id="modalAvance" class="modal-avance">
  <div class="modal-avance-content">
    <span class="close-avance" id="closeAvance">&times;</span>
    <h2>Registrar Avance</h2>
    <form action="registrar_avance.php" id="formAvance" method="post">
      <label for="montoAvance">Monto del avance (Bs):</label>
      <input type="number" id="montoAvance" name="montoAvance" min="0" step="0.01" required>

      <label for="metodoPago">Método de pago:</label>
      <select id="metodoPago" name="metodoPago" required>
        <option value="BS">Efectivo</option>
        <option value="Pago Movil">Pago Móvil</option>
      </select>

      <label for="porcentajeGanancia">Porcentaje de ganancia (%):</label>
      <input type="number" id="porcentajeGanancia" name="porcentajeGanancia" min="0" step="0.01" required>

      <div class="avance-total">
        <label>Total a cobrar (Bs):</label>
        <span id="totalAvance">0.00</span>
      </div>
      <?php if (!isset($_SESSION['dolar']) || !is_numeric($_SESSION['dolar']) || floatval($_SESSION['dolar']) <= 0): ?>
        <div style="color:red; margin:10px 0; font-weight:bold;">¡Debe definir la tasa de dólar antes de registrar avances!</div>
      <?php endif; ?>
      <button type="submit" id="btnRegistrarAvance" <?php if (!isset($_SESSION['dolar']) || !is_numeric($_SESSION['dolar']) || floatval($_SESSION['dolar']) <= 0) echo 'disabled'; ?>>Registrar</button>
    </form>
  </div>
</div>
<script>
// Mostrar/ocultar modal (puedes adaptar el trigger)
document.getElementById('closeAvance').onclick = function() {
  document.getElementById('modalAvance').style.display = 'none';
};
// Cálculo en tiempo real
document.getElementById('formAvance').oninput = function() {
  const monto = parseFloat(document.getElementById('montoAvance').value) || 0;
  const porcentaje = parseFloat(document.getElementById('porcentajeGanancia').value) || 0;
  const total = monto + (monto * porcentaje / 100);
  document.getElementById('totalAvance').textContent = total.toFixed(2);
};
// Al enviar el formulario, registrar avance por AJAX y actualizar la tabla visual si es exitoso
/*document.getElementById('formAvance').onsubmit = function(e) {
  e.preventDefault();
  const monto = parseFloat(document.getElementById('montoAvance').value) || 0;
  const metodo = document.getElementById('metodoPago').value;
  const porcentaje = parseFloat(document.getElementById('porcentajeGanancia').value) || 0;
  const formData = new FormData(this);
  fetch('registrar_avance.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if(data.success) {
      // No sumamos visualmente, el avance ya aparecerá en la tabla de montos diarios al recargar
      document.getElementById('modalAvance').style.display = 'none';
      document.getElementById('formAvance').reset();
      document.getElementById('totalAvance').textContent = '0.00';
      alert('Avance registrado correctamente como venta.');
      // Opcional: recargar la página para ver el cambio reflejado
      location.reload();
    } else {
      alert('Error: ' + (data.error || 'No se pudo registrar el avance.'));
    }
  })
  .catch(() => {
    alert('Error de conexión al registrar el avance.');
  });
};*/
</script>
<script>
// Mostrar el modal al hacer clic en el botón
document.getElementById('btnAvance').onclick = function() {
  document.getElementById('modalAvance').style.display = 'block';
};
</script>
</body>
</html>