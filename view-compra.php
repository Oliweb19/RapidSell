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
                <?php
                    include_once 'conexion_BD.php'; 
                    $id = $_GET['id'];
                    $por_pagina = 10;
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $inicio = ($pagina - 1) * $por_pagina;
                    // Contar total de productos en la compra
                    $sql_total = "SELECT COUNT(*) as total FROM compra WHERE id_compra = '$id'";
                    $res_total = mysqli_query($conexion, $sql_total);
                    $row_total = mysqli_fetch_assoc($res_total);
                    $total_productos = $row_total['total'];
                    $total_paginas = ceil($total_productos / $por_pagina);
                    // Obtener productos de la página actual
                    $sql = "SELECT ventas.id_compra, productos.nombre_producto, productos.precio, productos.precio_bs, compra.cantidad_producto, ventas.total_pagar, ventas.total_pagar_bs
                            FROM ventas
                            JOIN compra ON ventas.id_compra = compra.id_compra
                            JOIN productos ON compra.id_producto = productos.id_producto
                            WHERE compra.id_compra = '$id'
                            LIMIT $inicio, $por_pagina";
                    $resultado = mysqli_query($conexion,$sql);
                ?>
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
                        $total = 0;
                        $total1 = 0;
                        while($mostrar = mysqli_fetch_array($resultado)){ 
                            $total = $mostrar['total_pagar'];
                            $total1 = $mostrar['total_pagar_bs'];
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
                        }
                    ?>
                    <thead>
                        <tr>
                            <th><h3>Total a Pagar en $: <?php echo $total; ?>$</h3></th>
                            <th><h3>Total a Pagar en Bs: <?php echo $total1; ?>bs</h3></th>
                        </tr>
                    </thead>
                </table>
                <div style="margin-top:20px; text-align:center;">
                  <?php if($pagina > 1){ ?>
                    <a href="?id=<?php echo $id; ?>&pagina=<?php echo $pagina-1; ?>" class="btn-paginador" title="Anterior">
                      <i class="fa-solid fa-chevron-left" style="font-weight:900;font-size:1.25em;text-shadow:0 0 1px #222;"></i>
                    </a>
                  <?php } ?>
                  <span style="margin:0 10px;">Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?></span>
                  <?php if($pagina < $total_paginas){ ?>
                    <a href="?id=<?php echo $id; ?>&pagina=<?php echo $pagina+1; ?>" class="btn-paginador" title="Siguiente">
                      <i class="fa-solid fa-chevron-right" style="font-weight:900;font-size:1.25em;text-shadow:0 0 1px #222;"></i>
                    </a>
                  <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>