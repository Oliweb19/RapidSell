<?php
    session_start();
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
    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
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
            
            <div class="cont-cart">
                <h2>Productos</h2>
                <div class="cart-num">
                    <a href="view-cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                    <span>
                    <?php 
                        $ver = "0";

                        if(isset($_SESSION['carrito'])){
                            $carrito_mio = $_SESSION['carrito'];
                            $ver = count($carrito_mio);
                        }
                        
                        echo $ver;
                    ?>
                    </span>
                </div>
                
            </div>
            
            <div class="bars-products">
              <h2>Buscar Productos</h2>
              <form action="venta.php" method="post" class="monto">
                <input type="search" name="producto" id="" placeholder="Codigo de Barra | Nombre" class="especial" autofocus>
                <button type="submit" id="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
              </form>
              <?php
                if(isset($_POST['producto']) == NULL){ 
              ?>    
            </div>

            <!-- Aqui se puede agregar algo -->
            
            <?php
            }
                else{      
            ?>
            <!-- Modal para mostrar el producto buscado -->
            <div id="modal-producto" class="modal">
                <div class="modal-content">
                    <span class="close" id="close-modal">&times;</span>
                    <ul>
                        <?php
                            include_once 'conexion_BD.php';

                            $prod = $_POST['producto'];
                                        
                            $sql = "SELECT * FROM productos 
                            WHERE (cerial LIKE '%$prod%' OR nombre_producto LIKE '%$prod%')
                            AND estatus = 1";
                            $resultado = mysqli_query($conexion,$sql);

                            if(mysqli_num_rows($resultado) > 0){
                                while($mostrar = mysqli_fetch_array($resultado)){
                        ?>
                        <li class="splide__slide search">
                            <form action="cart.php" method="post">
                                <div class="precio">
                                    <input type="hidden" name="id" value="<?php echo $mostrar['id_producto']; ?>">
                                    <span><?php echo $mostrar['precio']; ?></span>
                                    <input type="hidden" name="precio" value="<?php echo $mostrar['precio']; ?>">
                                    <input type="hidden" name="precio_bs" value="<?php echo $mostrar['precio_bs']; ?>">
                                </div>
                                <div class="img-product">
                                    <img src="img/RapidSell mini-logo.png" alt="" srcset=" ">
                                </div>
                                <div class="bars-nom">
                                    <h3><?php echo $mostrar['nombre_producto']; ?></h3>
                                    <input type="hidden" name="producto" value="<?php echo $mostrar['nombre_producto']; ?>">
                                    <input type="number" name="cant" id="" value="1">
                                </div>
                                <div class="bars-btn-cart">
                                    <button type="submit" id="btn-cart"><i class="fa-solid fa-cart-shopping"></i></button> 
                                </div>
                            </form> 
                        </li>
                        <?php
                                }
                            }
                            else{
                                echo '<h2>No se ha encontrado resultado!!</h2>';
                            }
                        ?> 
                    </ul> 
                </div>
            </div>
            <?php
                }
            ?> 
        </div>
    </div>

    <div id="modal-cart" class="modal modal-cart">
        <div class="modal-content modal-cart-content">
            <span class="close" id="close-cart-modal">&times;</span>
            <h2>Tu Carrito</h2>
            <?php
                if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0){
                    $total = 0;
            ?>
                <div class="bars-products">
                    <h2 class="cart-title">Productos del Carrito</h2>
                    <div class="cart-table-wrapper">
                    <table class="cart-table">
                        <thead>
                          <tr>
                            <th>Productos</th>
                            <th>Precio $</th>
                            <th>Precio Bs</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                            $carrito_mio = $_SESSION['carrito'];
                            for ($i = 0; $i < count($carrito_mio); $i++) { 
                        ?>
                          <tr>
                            <td><?php echo $carrito_mio[$i]['titulo']?></td>
                            <td><?php echo $carrito_mio[$i]['precio']?></td>
                            <td><?php echo $carrito_mio[$i]['precio_bs']?></td>
                            <td><?php echo $carrito_mio[$i]['cantidad']?></td>
                            <td>
                                <a href="delete-cart.php?id=<?php
                                    $elemento = $carrito_mio[$i];
                                    $indice = array_search($elemento, $carrito_mio); 
                                    echo $indice; 
                                ?>" class="delet" id="trash">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                            </td>
                          </tr>
                        <?php
                            }
                        ?>
                        </tbody>
                        <tfoot>
                            <?php
                                $to = array_column($carrito_mio, 'total');
                                $total = array_sum($to);

                                $to1 = array_column($carrito_mio, 'total1');
                                $total1 = array_sum($to1);
                            ?>
                          <tr>
                            <td colspan="2" class="cart-total"><h3>Total a Pagar en $: <?php echo $total; ?>$</h3></td> 
                            <td colspan="3" class="cart-total"><h3>Total a Pagar en Bs: <?php echo $total1; ?>bs</h3></td>
                          </tr>
                        </tfoot>
                      </table>
                      </div>
                      <div class="cont-form-cart">
                        <form action="comprar.php" method="post" class="cart-form">
                            <div class="cart-form-group">
                                <select name="mdp" id="mdp" REQUIRED>
                                    <option value="">Metodo de Pago</option>
                                    <option value="BS">BS</option>
                                    <option value="Dolares">$</option>
                                    <option value="Punto">Punto</option>
                                    <option value="Pago Movil">Pago Movil</option>
                                    <option value="Credito">Credito</option>
                                </select>
                                <input type="text" name="nombre" id="nombre-credito" placeholder="Nombre del Comprador">
                            </div>
                            <input type="hidden" name="total" value="<?php echo $total; ?>">
                            <input type="hidden" name="total1" value="<?php echo $total1; ?>">
                            <div class="cont-btn-cart">
                                <button type="submit" class="btn-cart" id="comprar">Comprar</button>
                                <a href="borrarcarro.php" class="btn-cart btn-vaciar" id="vaciar">Vaciar Carrito</a>
                            </div>
                        </form>
                      </div>
                </div>
            <?php
            }else{
                echo "<p>No hay productos en el carrito.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var metodoPago = document.getElementById('mdp');
            var nombreCredito = document.getElementById('nombre-credito');
            metodoPago.addEventListener('change', function() {
                if (this.value === 'Credito') {
                    nombreCredito.classList.add('show');
                    nombreCredito.required = true;
                } else {
                    nombreCredito.classList.remove('show');
                    nombreCredito.required = false;
                    nombreCredito.value = '';
                }
            });
        });
    </script>

    <script>
        // Abrir modal del carrito al hacer clic en el icono
        document.addEventListener('DOMContentLoaded', function() {
            var cartIcon = document.querySelector('.cart-num a');
            var cartModal = document.getElementById('modal-cart');
            var closeCartBtn = document.getElementById('close-cart-modal');

            cartIcon.addEventListener('click', function(e){
                e.preventDefault();
                cartModal.style.display = 'block';
            });

            closeCartBtn.onclick = function() {
                cartModal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == cartModal) {
                    cartModal.style.display = 'none';
                }
            }
        });
    </script>

    <script>
        // Mostrar el modal automáticamente si existe resultado
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('modal-producto');
            var closeBtn = document.getElementById('close-modal');
            if (modal) {
                modal.style.display = 'block';
                closeBtn.onclick = function() {
                    modal.style.display = 'none';
                }
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }
            }
        });
    </script>

</body>
</html>