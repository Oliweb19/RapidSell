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
                <h2>Creditos</h2>
                <?php
          // Paginador créditos pendientes (agrupar por nombre y sumar montos)
          $por_pagina = 10;
          $pagina = isset($_GET['pagina_pend']) ? (int)$_GET['pagina_pend'] : 1;
          $inicio = ($pagina - 1) * $por_pagina;
          // Contar nombres distintos para paginación
          $sql_total = "SELECT COUNT(DISTINCT compra.nombre) AS total FROM credito 
                 JOIN ventas ON ventas.id_ventas = credito.id_venta 
                 JOIN compra ON ventas.id_compra = compra.id_compra 
                 WHERE credito.estatu = 1";
          $res_total = mysqli_query($conexion, $sql_total);
          $row_total = mysqli_fetch_assoc($res_total);
          $total_creditos = $row_total['total'];
          $total_paginas = ceil($total_creditos / $por_pagina);
          // Seleccionar por nombre, sumar montos y tomar la fecha más reciente y el id_credito mínimo para acciones
          $sql = "SELECT compra.nombre AS nombre_deudor, 
                 GROUP_CONCAT(DISTINCT ventas.id_compra SEPARATOR ',') AS facturas, 
                 SUM(CAST(ventas.total_pagar AS DECIMAL(10,2))) AS total_suma, 
                 MAX(STR_TO_DATE(ventas.fecha, '%d/%m/%Y')) AS fecha_orden, 
                 MIN(credito.id_credito) AS id_credito_accion 
              FROM credito 
              JOIN ventas ON ventas.id_ventas = credito.id_venta 
              JOIN compra ON ventas.id_compra = compra.id_compra 
              WHERE credito.estatu = 1
              GROUP BY compra.nombre
              ORDER BY fecha_orden DESC
              LIMIT $inicio, $por_pagina";
          $resultado = mysqli_query($conexion,$sql);
                ?>
                <table class="index-tabla">
                    <thead>
                      <tr>
                        <th>Nombre del Deudor</th>
                        <th>Monto a Cancelar</th>
                        <th>Estatu de la Venta</th>
                        <th>Fecha de Venta</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <?php while($mostrar = mysqli_fetch_array($resultado)){ ?>
                    <tbody>
                      <tr>
                        <td><?php echo htmlspecialchars($mostrar['nombre_deudor']); ?></td>
                        <td><?php echo number_format($mostrar['total_suma'], 2); ?>$</td> 
                        <td>
                          <?php echo "No ha Pagado"; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($mostrar['fecha_orden'])); ?></td> 
                        <td>
                          <a href="update-credito.php?id=<?php echo $mostrar['id_credito_accion']; ?>" id="trash"><i class="fa-regular fa-trash-can"></i></a>
                        </td>
                      </tr>
                    </tbody>
                    <?php } ?>
                </table>
                <div style="margin-top:20px; text-align:center;">
                  <?php if($pagina > 1){ ?>
                    <a href="?pagina_pend=<?php echo $pagina-1; ?>" class="btn-paginador" title="Anterior">
                      <i class="fa-solid fa-chevron-left paginador-flecha"></i>
                    </a>
                  <?php } ?>
                  <span style="margin:0 10px;">Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?></span>
                  <?php if($pagina < $total_paginas){ ?>
                    <a href="?pagina_pend=<?php echo $pagina+1; ?>" class="btn-paginador" title="Siguiente">
                      <i class="fa-solid fa-chevron-right paginador-flecha"></i>
                    </a>
                  <?php } ?>
                </div>
            </div>
            <div class="bars-products">
                <h2>Creditos ya pagados</h2>
                <?php
          // Paginador créditos pagados (agrupar por nombre y sumar montos)
          $por_pagina_pag = 10;
          $pagina_pag = isset($_GET['pagina_pag']) ? (int)$_GET['pagina_pag'] : 1;
          $inicio_pag = ($pagina_pag - 1) * $por_pagina_pag;
          // Contar nombres distintos para paginación
          $sql_total_pag = "SELECT COUNT(DISTINCT compra.nombre) AS total FROM credito 
                   JOIN ventas ON ventas.id_ventas = credito.id_venta 
                   JOIN compra ON ventas.id_compra = compra.id_compra 
                   WHERE credito.estatu = 0";
          $res_total_pag = mysqli_query($conexion, $sql_total_pag);
          $row_total_pag = mysqli_fetch_assoc($res_total_pag);
          $total_creditos_pag = $row_total_pag['total'];
          $total_paginas_pag = ceil($total_creditos_pag / $por_pagina_pag);
          $sql = "SELECT compra.nombre AS nombre_deudor, 
                 GROUP_CONCAT(DISTINCT ventas.id_compra SEPARATOR ',') AS facturas, 
                 SUM(CAST(ventas.total_pagar AS DECIMAL(10,2))) AS total_suma, 
                 MAX(STR_TO_DATE(ventas.fecha, '%d/%m/%Y')) AS fecha_orden, 
                 MIN(credito.id_credito) AS id_credito_accion 
              FROM credito 
              JOIN ventas ON ventas.id_ventas = credito.id_venta 
              JOIN compra ON ventas.id_compra = compra.id_compra 
              WHERE credito.estatu = 0
              GROUP BY compra.nombre
              ORDER BY fecha_orden DESC
              LIMIT $inicio_pag, $por_pagina_pag";
          $resultado = mysqli_query($conexion,$sql);
                ?>
                <table class="index-tabla">
                    <thead>
                      <tr>
                        <th>Nombre del Deudor</th>
                        <th>Monto a Cancelar</th>
                        <th>Estatu de la Venta</th>
                        <th>Fecha de Venta</th>
                      </tr>
                    </thead>
                    <?php while($mostrar = mysqli_fetch_array($resultado)){ ?>
                    <tbody>
                      <tr>
                        <td><?php echo htmlspecialchars($mostrar['nombre_deudor']); ?></td>
                        <td><?php echo number_format($mostrar['total_suma'], 2); ?>$</td> 
                        <td>
                          <?php echo "Cancelado"; ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($mostrar['fecha_orden'])); ?></td> 
                      </tr>
                    </tbody>
                    <?php } ?>
                </table>
                <div style="margin-top:20px; text-align:center;">
                  <?php if($pagina_pag > 1){ ?>
                    <a href="?pagina_pag=<?php echo $pagina_pag-1; ?>" class="btn-paginador" title="Anterior">
                      <i class="fa-solid fa-chevron-left paginador-flecha"></i>
                    </a>
                  <?php } ?>
                  <span style="margin:0 10px;">Página <?php echo $pagina_pag; ?> de <?php echo $total_paginas_pag; ?></span>
                  <?php if($pagina_pag < $total_paginas_pag){ ?>
                    <a href="?pagina_pag=<?php echo $pagina_pag+1; ?>" class="btn-paginador" title="Siguiente">
                      <i class="fa-solid fa-chevron-right paginador-flecha"></i>
                    </a>
                  <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>