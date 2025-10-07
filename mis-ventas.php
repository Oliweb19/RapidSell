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
    <style>
      .paginador-flecha {
        font-weight: 900;
        font-size: 1.25em;
        text-shadow: 0 0 1px #222;
      }
      .btn-paginador {
        display: inline-block;
        padding: 6px 12px;
        margin: 0 2px;
        color: #222;
        background: #f2f2f2;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.2s;
      }
      .btn-paginador:hover {
        background: #e0e0e0;
      }
    </style>
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
                    // Paginador
                    $por_pagina = 10;
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $inicio = ($pagina - 1) * $por_pagina;
                    $filtro_fecha = false;
                    $fecha_actual = date("d/m/Y");
                    if(isset($_POST['date']) && !empty($_POST['date'])) {
                      $fecha = $_POST['date'];
                      $filtro_fecha = true;
                    } else if(isset($_GET['date']) && !empty($_GET['date'])) {
                      $fecha = $_GET['date'];
                      $filtro_fecha = true;
                    } else {
                      $fecha = $fecha_actual;
                    }
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
                      // Contar total de ventas
                      $sql_total = "SELECT COUNT(*) as total FROM ventas WHERE fecha='$fecha'";
                      $res_total = mysqli_query($conexion, $sql_total);
                      $row_total = mysqli_fetch_assoc($res_total);
                      $total_ventas = $row_total['total'];
                      $total_paginas = ceil($total_ventas / $por_pagina);
                      // Obtener ventas de la página actual
                      $sql = "SELECT * FROM ventas WHERE fecha='$fecha' ORDER BY id_compra DESC LIMIT $inicio, $por_pagina";
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
                  <div style="margin-top:20px; text-align:center;">
                    <?php if($pagina > 1){ ?>
                      <a href="?pagina=<?php echo $pagina-1; ?><?php echo $filtro_fecha ? '&date='.urlencode($fecha) : ''; ?>" class="btn-paginador" title="Anterior">
                        <i class="fa-solid fa-chevron-left paginador-flecha"></i>
                      </a>
                    <?php } ?>
                    <span style="margin:0 10px;">Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?></span>
                    <?php if($pagina < $total_paginas){ ?>
                      <a href="?pagina=<?php echo $pagina+1; ?><?php echo $filtro_fecha ? '&date='.urlencode($fecha) : ''; ?>" class="btn-paginador" title="Siguiente">
                        <i class="fa-solid fa-chevron-right paginador-flecha"></i>
                      </a>
                    <?php } ?>
                  </div>
            </div>
        </div>
    </div>
</body>
</html>