<?php
  session_start();

  if($_SESSION['carrito'] == NULL){
    header("Location: venta.php");
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
                <h2>Productos del Carrito</h2>
                <table>
                    <thead>
                      <tr>
                        <th>Productos</th>
                        <th>Precio $</th>
                        <th>Precio Bs</th>
                        <th>Cantidad</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <?php
                        $carrito_mio = $_SESSION['carrito'];
                        for ($i = 0; $i < count($carrito_mio); $i++) { 
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $carrito_mio[$i]['titulo']?></td>
                        <td><?php echo $carrito_mio[$i]['precio']?></td>
                        <td><?php echo $carrito_mio[$i]['precio_bs']?></td>
                        <td><?php echo $carrito_mio[$i]['cantidad']?></td>
                        <td>
                            <a href="delete-cart.php?id=
                            <?php
                                $elemento = $carrito_mio[$i];
                                $indice = array_search($elemento, $carrito_mio); 
                                echo $indice; 
                            ?>" class="delet" id="trash">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </td>
                      </tr>
                    </tbody>
                    <?php
                        }
                    ?>
                    <thead>
                        <?php
                            $to = array_column($carrito_mio, 'total');
                            $total = array_sum($to);

                            $to1 = array_column($carrito_mio, 'total1');
                            $total1 = array_sum($to1);
                        ?>
                      <tr>
                        <th><h3>Total a Pagar en $: <?php echo $total; ?>$</h3></th> 
                        <th><h3>Total a Pagar en Bs: <?php echo $total1; ?>bs</h3></th>
                      </tr>
                    </thead>
                  </table>
                  <div class="cont-form-cart">
                    <form action="comprar.php" method="post">
                        <input type="number" name="cedula" id="" placeholder="Cedula del Comprador" REQUIRED>
                        <input type="text" name="nombre" id="" placeholder="Nombre del Comprador" REQUIRED>
                        <select name="mdp" id="" REQUIRED>
                          <option value="">Metodo de Pago</option>
                          <option value="BS">BS</option>
                          <option value="Dolares">$</option>
                          <option value="Punto">Punto</option>
                          <option value="Biopago">Biopago</option>
                          <option value="Pago Movil">Pago Movil</option>
                          <option value="Zelle">Zelle</option>
                          <option value="Credito">Credito</option>
                        </select>
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <input type="hidden" name="total1" value="<?php echo $total1; ?>">
                        <button type="submit" class="btn-cart" id="comprar">Comprar</button>
                    </form>
                  </div>
                  <div class="cont-btn-cart">
                    <a href="venta.php" class="btn-cart">Volver</a>
                    <a href="borrarcarro.php" class="btn-cart" id="vaciar">Vaciar Carrito</a>
                  </div>
            </div>
        </div>
    </div>
</body>
</html>