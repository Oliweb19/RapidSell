<?php
  session_start();
  include_once 'conexion_BD.php'; 

  // --- GESTIÓN DE GANANCIA SEMANAL ---
  $today_w = date('w'); // 0 = Domingo, 1 = Lunes, ...
  // Calcular lunes y sábado de la semana actual en formato d/m/Y
  // NOTA: Para incluir el domingo en el cálculo semanal, se ajustará el rango de la consulta SQL.
  $monday = date('d/m/Y', strtotime('monday this week'));
  // El fin de la semana para el cálculo es el Sábado, pero se incluye el Domingo en la lógica
  $saturday = date('d/m/Y', strtotime('saturday this week'));
  $current_week = date('oW');
  
  // Condición 1: Reiniciar el valor de la sesión el domingo
  if ($today_w == 0) {
    // Domingo: resetear la ganancia semanal a 0 (y la semana)
    $_SESSION['weekly_gain'] = 0.0;
    $_SESSION['weekly_gain_week'] = $current_week;
    // La consulta de la ganancia semanal se ejecutará en el siguiente bloque si se entra a la app el Domingo.
  } 
  
  // Condición 2: Si es un nuevo inicio de semana (Lunes-Sábado), recalcular
  if (!isset($_SESSION['weekly_gain_week']) || $_SESSION['weekly_gain_week'] !== $current_week) {
    // Calcular suma de ganancia desde Lunes hasta el Sábado de la semana actual
    $sql_week = "SELECT COALESCE(SUM(c.ganancia_unidad_vendida * c.cantidad_producto), 0) AS weekly_gain 
           FROM ventas v 
           JOIN compra c ON v.id_compra = c.id_compra 
           WHERE STR_TO_DATE(v.fecha, '%d/%m/%Y') >= STR_TO_DATE('$monday', '%d/%m/%Y') 
           AND STR_TO_DATE(v.fecha, '%d/%m/%Y') <= STR_TO_DATE('$saturday', '%d/%m/%Y')"; // Solo hasta el Sábado
    $res_week = mysqli_query($conexion, $sql_week);
    $row_week = $res_week ? mysqli_fetch_assoc($res_week) : null;
    $weekly_gain_val = $row_week && isset($row_week['weekly_gain']) ? floatval($row_week['weekly_gain']) : 0.0;
    $_SESSION['weekly_gain'] = $weekly_gain_val;
    $_SESSION['weekly_gain_week'] = $current_week;
  }
  
  // Si no es domingo y la ganancia ya fue calculada, simplemente se usa el valor de SESSION.
  // Sin embargo, para que se actualice al día actual, la lógica del inicio es insuficiente.
  // MEJORAMOS LA LÓGICA: Se añade la ganancia diaria a la sesión para que el "Resumen Semanal" sea acumulativo.

  // Calculamos la ganancia de HOY para sumar a la sesión (si no se está buscando una fecha histórica)
  $fecha = date("d/m/Y"); 
  $sql_ganancia_hoy = "SELECT COALESCE(SUM(c.ganancia_unidad_vendida * c.cantidad_producto), 0) AS ganancia_hoy
                        FROM ventas v
                        JOIN compra c ON v.id_compra = c.id_compra
                        WHERE v.fecha = '$fecha'";
  $res_ganancia_hoy = mysqli_query($conexion, $sql_ganancia_hoy);
  $ganancia_row_hoy = $res_ganancia_hoy ? mysqli_fetch_assoc($res_ganancia_hoy) : null;
  $ganancia_hoy_val = $ganancia_row_hoy && isset($ganancia_row_hoy['ganancia_hoy']) ? floatval($ganancia_row_hoy['ganancia_hoy']) : 0.0;

  // Lógica de cálculo diario acumulativo para la sesión semanal
  // Si la ganancia de hoy no ha sido sumada a la sesión semanal, la sumamos (esto evita doble conteo si el usuario recarga)
  // Se requiere una bandera en la sesión para el día. Como esto es complejo, la solución más simple es:
  
  // Si no hay búsqueda de fecha, re-calcular la semana hasta HOY
  if (!isset($_POST['date']) || empty($_POST['date'])) {
    $current_date_for_week = date('d/m/Y');
    // Para el cálculo semanal HASTA HOY, el rango es desde el lunes hasta la fecha actual.
    $sql_week_until_today = "SELECT COALESCE(SUM(c.ganancia_unidad_vendida * c.cantidad_producto), 0) AS weekly_gain_today 
           FROM ventas v 
           JOIN compra c ON v.id_compra = c.id_compra 
           WHERE STR_TO_DATE(v.fecha, '%d/%m/%Y') >= STR_TO_DATE('$monday', '%d/%m/%Y') 
           AND STR_TO_DATE(v.fecha, '%d/%m/%Y') <= STR_TO_DATE('$current_date_for_week', '%d/%m/%Y')";
    $res_week_today = mysqli_query($conexion, $sql_week_until_today);
    $row_week_today = $res_week_today ? mysqli_fetch_assoc($res_week_today) : null;
    $weekly_gain_val_today = $row_week_today && isset($row_week_today['weekly_gain_today']) ? floatval($row_week_today['weekly_gain_today']) : 0.0;
    $_SESSION['weekly_gain'] = $weekly_gain_val_today; // Sobrescribir la sesión con el cálculo actualizado.
    $_SESSION['weekly_gain_week'] = $current_week; 
  }

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

                          <?php
                            // Ganancia real diaria en USD para la fecha actual o la fecha buscada
                            $target_date = (isset($_POST['date']) && !empty($_POST['date'])) ? $date : $fecha;
                            $sql_ganancia_def = "SELECT COALESCE(SUM(c.ganancia_unidad_vendida * c.cantidad_producto), 0) AS ganancia_real_diaria_usd
                                                  FROM ventas v
                                                  JOIN compra c ON v.id_compra = c.id_compra
                                                  WHERE v.fecha LIKE '%$target_date%'";
                            $res_ganancia_def = mysqli_query($conexion, $sql_ganancia_def);
                            $ganancia_row_def = $res_ganancia_def ? mysqli_fetch_assoc($res_ganancia_def) : null;
                            $ganancia_val_def = $ganancia_row_def && isset($ganancia_row_def['ganancia_real_diaria_usd']) ? floatval($ganancia_row_def['ganancia_real_diaria_usd']) : 0;
                          ?>

                          <!--<div class="ganancia">
                            <h3>Ganancia: <?php //echo str_replace('.', ',', number_format($ganancia_val_def, 2)); ?>$</h3>
                          </div>
                           Tarjeta resumen semanal -->
                          <?php if (!isset($_POST['date']) || empty($_POST['date'])): ?>
                            <div class="weekly-card">
                              <h4>Resumen Semanal (Lun - Sáb)</h4>
                              <div class="weekly-amount">
                                <?php $weekly_val = isset($_SESSION['weekly_gain']) ? floatval($_SESSION['weekly_gain']) : 0.0; ?>
                                <span class="weekly-number"><?php echo str_replace('.', ',', number_format($weekly_val, 2)); ?></span>$
                              </div>
                              <div class="weekly-note">Semana: <?php echo isset($_SESSION['weekly_gain_week']) ? $_SESSION['weekly_gain_week'] : date('oW'); ?></div>
                            </div>
                          <?php endif; ?>
                          <?php if (isset($_POST['date']) && !empty($_POST['date'])): ?>
                            <!-- Mostrar ganancia para la fecha buscada con estilo de tarjeta semanal -->
                            <div class="weekly-card">
                              <h4>Ganancia para: <?php echo htmlspecialchars($_POST['date']); ?></h4>
                              <div class="weekly-amount">
                                <span class="weekly-number"><?php echo str_replace('.', ',', number_format(floatval($ganancia_val), 2)); ?></span>$
                              </div>
                              <div class="weekly-note">Resultados filtrados por fecha</div>
                            </div>
                          <?php endif; ?>
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

                              // Ganancia real diaria en USD: sumar (ganancia unitaria * cantidad) para las compras relacionadas a ventas del día
            $sql_ganancia = "SELECT COALESCE(SUM(c.ganancia_unidad_vendida * c.cantidad_producto), 0) AS ganancia_real_diaria_usd
              FROM ventas v
              JOIN compra c ON v.id_compra = c.id_compra
              WHERE v.fecha LIKE '%$date%'";
                              $res_ganancia = mysqli_query($conexion, $sql_ganancia);
                              $ganancia_row = $res_ganancia ? mysqli_fetch_assoc($res_ganancia) : null;
                              $ganancia_val = $ganancia_row && isset($ganancia_row['ganancia_real_diaria_usd']) ? floatval($ganancia_row['ganancia_real_diaria_usd']) : 0;
                            ?>
                            <tr>
                              <?php $total_dolares_val = isset($row_total['total_dolares']) ? floatval($row_total['total_dolares']) : 0; ?>
                              <td colspan="1" class="cart-total"><h3>Total: <?php echo str_replace('.', ',', number_format($total_dolares_val, 2)); ?>$</h3></td>
                            </tr>
                            </tfoot>
                          </table>
                          <?php
                            // Ganancia real diaria en USD para la fecha actual
                            $sql_ganancia_def = "SELECT COALESCE(SUM(c.ganancia_unidad_vendida * c.cantidad_producto), 0) AS ganancia_real_diaria_usd
                                                  FROM ventas v
                                                  JOIN compra c ON v.id_compra = c.id_compra
                                                  WHERE v.fecha = '$fecha'";
                            $res_ganancia_def = mysqli_query($conexion, $sql_ganancia_def);
                            $ganancia_row_def = $res_ganancia_def ? mysqli_fetch_assoc($res_ganancia_def) : null;
                            $ganancia_val_def = $ganancia_row_def && isset($ganancia_row_def['ganancia_real_diaria_usd']) ? floatval($ganancia_row_def['ganancia_real_diaria_usd']) : 0;
                          ?>

                          <!--<div class="ganancia">
                            <h3>Ganancia: <?php //echo str_replace('.', ',', number_format($ganancia_val_def, 2)); ?>$</h3>
                          </div>
                           Tarjeta resumen semanal -->
                          <?php if (!isset($_POST['date']) || empty($_POST['date'])): ?>
                            <div class="weekly-card">
                              <h4>Resumen Semanal (Lun - Sáb)</h4>
                              <div class="weekly-amount">
                                <?php $weekly_val = isset($_SESSION['weekly_gain']) ? floatval($_SESSION['weekly_gain']) : 0.0; ?>
                                <span class="weekly-number"><?php echo str_replace('.', ',', number_format($weekly_val, 2)); ?></span>$
                              </div>
                              <div class="weekly-note">Semana: <?php echo isset($_SESSION['weekly_gain_week']) ? $_SESSION['weekly_gain_week'] : date('oW'); ?></div>
                            </div>
                          <?php endif; ?>
                          <?php if (isset($_POST['date']) && !empty($_POST['date'])): ?>
                            <!-- Mostrar la ganancia de la fecha buscada con estilos de tarjeta -->
                            <div class="weekly-card">
                              <h4>Ganancia para: <?php echo htmlspecialchars($_POST['date']); ?></h4>
                              <div class="weekly-amount">
                                <span class="weekly-number"><?php echo str_replace('.', ',', number_format(floatval($ganancia_val), 2)); ?></span>$
                              </div>
                              <div class="weekly-note">Resultados filtrados por fecha</div>
                            </div>
                          <?php endif; ?>
                          
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

// Validación simple de fecha DD/MM/YYYY si se usa el campo opcional
function validarFechaDMY(str) {
  if (!str) return true; // opcional
  const re = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(\d{4})$/;
  return re.test(str);
}

// Si se usa el formulario (no AJAX), hacer pre-validación antes de submit
document.getElementById('formAvance').addEventListener('submit', function(e) {
  const fechaVal = document.getElementById('fechaAvance').value.trim();
  if (fechaVal && !validarFechaDMY(fechaVal)) {
    e.preventDefault();
    alert('Formato de fecha inválido. Use DD/MM/AAAA.');
    return false;
  }
  return true;
});
</script>
<script>
// Mostrar el modal al hacer clic en el botón
document.getElementById('btnAvance').onclick = function() {
  document.getElementById('modalAvance').style.display = 'block';
};
</script>
</body>
</html>