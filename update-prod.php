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
            <div class="update">
                <?php 
                    include_once 'conexion_BD.php';

                    $id = $_GET['id'];

                    $sql = "SELECT * FROM productos WHERE id_producto = '$id'";
                    $resultado = mysqli_query($conexion,$sql);
                    $mostrar = mysqli_fetch_array($resultado);
                ?>
                <div class="cont-update">
                    <h2>Modificar <?php echo $mostrar['nombre_producto']; ?></h2>
                </div>
                <div class="bars-update">
                    <div class="bars-img-update">
                        <img src="img/RapidSell.png" alt="">
                    </div>
                    <div class="bars-form-update">
                        <form action="up-prod.php" method="post" id="formUpdateProd">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div>
                                <label for="cerial">Código de barra: </label>
                                <input type="text" name="cerial" id="cerial" value="<?php echo $mostrar['cerial']; ?>">
                            </div>
                            <div>
                                <label for="nom">Nombre del Producto: </label>
                                <input type="text" name="nom" id="nom" value="<?php echo $mostrar['nombre_producto']; ?>"> 
                            </div>
                            <div>
                                <label for="unidades">Unidades por bulto: </label>
                                <input type="number" name="unidades" id="unidades" min="1" value="">
                            </div>
                            <div>
                                <label for="bultos">Cantidad de bultos: </label>
                                <input type="number" name="bultos" id="bultos" min="1" value="">
                            </div>
                            <div>
                                <label for="precio">Precio del bulto ($): </label>
                                <input type="text" name="precio" id="precio" value="<?php echo $mostrar['precio']; ?>">
                            </div>
                            <div>
                                <label for="porcentaje">Porcentaje de ganancia (%): </label>
                                <input type="number" name="porcentaje" id="porcentaje" min="0" step="0.01" value="">
                            </div>
                            <!-- Cuadro visual de stock y ganancia -->
                            <div id="calcResumen" style="margin:15px 0; padding:12px; background:#f7fafc; border-radius:8px; border:1px solid #e6eef6;">
                                <strong>Stock calculado:</strong> <span id="stockCalculado">0</span><br>
                                <strong>Ganancia estimada ($):</strong> <span id="gananciaCalculada">0.00</span>
                            </div>
                            <button type="submit">Modificar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function calcularResumen() {
        const unidades = parseFloat(document.getElementById('unidades').value) || 0;
        const bultos = parseFloat(document.getElementById('bultos').value) || 0;
        const precio = parseFloat(document.getElementById('precio').value) || 0;
        const porcentaje = parseFloat(document.getElementById('porcentaje').value) || 0;
        const stock = unidades * bultos;
        document.getElementById('stockCalculado').textContent = stock;
        // Calculo de ganancia igual que en PHP
        if (unidades > 0 && bultos > 0 && precio > 0) {
            const costo_bulto = precio;
            const porcentaje_ganancia = porcentaje / 100;
            const costo_unidad = costo_bulto / unidades;
            const precio_venta_unidad = costo_unidad / (1 - porcentaje_ganancia);
            const ganancia_unidad = precio_venta_unidad - costo_unidad;
            const ganancia_total = ganancia_unidad * unidades * bultos;
            document.getElementById('gananciaCalculada').textContent = ganancia_total.toFixed(2);
        } else {
            document.getElementById('gananciaCalculada').textContent = '0.00';
        }
    }
    document.getElementById('unidades').addEventListener('input', calcularResumen);
    document.getElementById('bultos').addEventListener('input', calcularResumen);
    document.getElementById('precio').addEventListener('input', calcularResumen);
    document.getElementById('porcentaje').addEventListener('input', calcularResumen);
    window.addEventListener('DOMContentLoaded', calcularResumen);
    </script>
</body>
</html>