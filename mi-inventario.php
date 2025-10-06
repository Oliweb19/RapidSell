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
              <h2>Buscar Productos</h2>
              <form action="mi-inventario.php" method="post" class="monto">
                <input type="search" name="producto" id="" placeholder="Nombre del Producto" class="especial" autofocus REQUIRED>
                <button type="submit" id="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
              </form>
              <br>
              <h2>Cambiar Precio en Bs</h2>
              <form action="precio_nuevo.php" method="post" class="monto">
                <input type="text" name="valor" id="buscador-bs" placeholder="Ingrese precio del $ en bs" class="especial" autofocus REQUIRED>
                <button type="submit" id="btn-search"><i class="fa-solid fa-arrows-rotate"></i></button>
              </form>
              <?php
                if(isset($_POST['producto']) == NULL){ 
              ?>    
            </div>
            <div class="bars-products">
                <h2>Todos los Productos</h2>
                <table class="index-tabla">
                    <thead>
                      <tr>
                        <th>Codigo</th> 
                        <th>Producto</th> 
                        <th>Precio $</th>
                        <th>Precio Bs</th>
                        <th>Ganancia</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <?php 
                      $sql = "SELECT * FROM productos 
                      WHERE estatus = 1";
                      $resultado = mysqli_query($conexion,$sql); 

                      while($mostrar = mysqli_fetch_array($resultado)){ 
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $mostrar['cerial']; ?></td>
                        <td><?php echo $mostrar['nombre_producto']; ?></td>
                        <td><?php echo $mostrar['precio']; ?>$</td>
                        <td>Bs. <?php echo $mostrar['precio_bs']; ?></td>
                        <td><?php echo $mostrar['ganancia']; ?>$</td>
                        <td><?php echo $mostrar['stock']; ?></td>
                        <td>
                          <a href="update-prod.php?id=<?php echo $mostrar['id_producto']; ?>" id="pen"><i class="fa-solid fa-pen"></i> </a>
                          <a href="delete-prod.php?id=<?php echo $mostrar['id_producto']; ?>" class="delet" id="trash"><i class="fa-regular fa-trash-can"></i></a>
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
                <h2>Todos los Productos</h2>
                <table>
                    <thead>
                      <tr>
                        <th>Codigo</th> 
                        <th>Producto</th> 
                        <th>Precio $</th>
                        <th>Precio Bs</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <?php 
                      $prod = $_POST['producto'];
                        
                      $sql = "SELECT * FROM productos 
                      WHERE nombre_producto LIKE '%$prod%'
                      AND estatus = 1";
                      $resultado = mysqli_query($conexion,$sql); 

                      while($mostrar = mysqli_fetch_array($resultado)){ 
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $mostrar['cerial']; ?></td>
                        <td><?php echo $mostrar['nombre_producto']; ?></td>
                        <td><?php echo $mostrar['precio']; ?>$</td>
                        <td>Bs. <?php echo $mostrar['precio_bs']; ?></td>
                        <td><?php echo $mostrar['stock']; ?></td>
                        <td>
                          <a href="update-prod.php?id=<?php echo $mostrar['id_producto']; ?>" id="pen"><i class="fa-solid fa-pen"></i> </a>
                          <a href="delete-prod.php?id=<?php echo $mostrar['id_producto']; ?>" class="delet" id="trash"><i class="fa-regular fa-trash-can"></i></a>
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
        <div class="bars-products"></div>
    </div>
    <!--- JS --->
    <script src="js/confirmacion.js"></script> 
</body>
</html>