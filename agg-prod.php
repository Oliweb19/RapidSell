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
            <div class="bars-prod">
                <div class="bars-prod-title">
                    <h2>Agregar Productos</h2>
                </div>
                <div class="bars-prod-input">
                    <form action="insert_prod.php" method="post"> 
                        <input type="number" name="cerial" id="" placeholder="Codigo de Barra" class="quiz" REQUIRED>
                        <input type="text" name="nom" id="" minlength="2" maxlength="40" placeholder="Nombre del Producto" class="quiz" REQUIRED>
                        <input type="number" name="unidades" id="" placeholder="Unidades" class="quiz" REQUIRED>
                        <input type="number" name="bultos" id="" placeholder="Bultos" class="quiz" REQUIRED>
                        <input type="text" name="precio" id="" placeholder="Precio del Bulto" class="quiz" REQUIRED>
                        <input type="number" name="stock" id="" placeholder="Porcentaje que quieres ganar" class="quiz" REQUIRED>

                        <button type="submit" id="btn-form">Vista Previa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-ganancia" class="modal modal-cart">
        <div class="modal-content modal-cart-content">
            <span class="close" id="close-ganancia-modal">&times;</span>
            <h2>Ganancia por Productos</h2>
            <div class="bars-products">
                <h2 class="cart-title">Productos</h2>
                <div class="cart-table-wrapper">
                    <table class="cart-table" id="tabla-ganancia">
                        <thead>
                            <tr>
                                <th>Porcentaje de Ganancia</th>
                                <th>Precio por Unidad</th>
                                <th>Ganancia por Unidad</th>
                                <th>Ganancia Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="modal-porcentaje"></td>
                                <td id="modal-precio-unidad"></td>
                                <td id="modal-ganancia-unidad"></td>
                                <td id="modal-ganancia-total"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form id="form-agregar-prod" action="insert_prod.php" method="post">
                    <input type="hidden" name="cerial" id="modal-cerial">
                    <input type="hidden" name="nom" id="modal-nom">
                    <input type="hidden" name="unidades" id="modal-unidades">
                    <input type="hidden" name="bultos" id="modal-bultos">
                    <input type="hidden" name="precio" id="modal-precio">
                    <input type="hidden" name="stock" id="modal-stock">
                    <button type="submit" class="btn-cart" style="margin-top:20px;">Agregar Producto</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.bars-prod-input form');
            const modal = document.getElementById('modal-ganancia');
            const closeBtn = document.getElementById('close-ganancia-modal');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Obtener valores
                const cerial = form.cerial.value;
                const nom = form.nom.value;
                const unidades = parseInt(form.unidades.value) || 0;
                const bultos = parseInt(form.bultos.value) || 0;
                const costo_bulto = parseFloat(form.precio.value) || 0;
                const porcentaje = parseFloat(form.stock.value) || 0;

                // Cálculos según tu ejemplo PHP
                const total_unidades = unidades * bultos;
                const costo_unidad = costo_bulto / (unidades > 0 ? unidades : 1);
                const porcentaje_ganancia = porcentaje / 100;
                const precio_venta_unidad = costo_unidad / (1 - porcentaje_ganancia);
                const ganancia_unidad = precio_venta_unidad - costo_unidad;
                const ganancia_total = ganancia_unidad * total_unidades;

                // Mostrar en el modal
                document.getElementById('modal-porcentaje').textContent = porcentaje + "%";
                document.getElementById('modal-precio-unidad').textContent = "$" + precio_venta_unidad.toFixed(2);
                document.getElementById('modal-ganancia-unidad').textContent = "$" + ganancia_unidad.toFixed(2);
                document.getElementById('modal-ganancia-total').textContent = "$" + ganancia_total.toFixed(2);

                // Pasar datos al formulario oculto
                document.getElementById('modal-cerial').value = cerial;
                document.getElementById('modal-nom').value = nom;
                document.getElementById('modal-unidades').value = unidades;
                document.getElementById('modal-bultos').value = bultos;
                document.getElementById('modal-precio').value = costo_bulto;
                document.getElementById('modal-stock').value = porcentaje;

                modal.style.display = 'block';
            });

            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>