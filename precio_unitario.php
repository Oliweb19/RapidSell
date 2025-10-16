<?php

// 1. CONFIGURACIÓN DE LA BASE DE DATOS (AJUSTA ESTOS VALORES)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rapidsell";

// 2. CONEXIÓN A LA BASE DE DATOS
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión exitosa a la base de datos '$dbname'.\n\n";

// 3. OBTENER TODOS LOS REGISTROS DE COMPRA
$sql_select_compras = "SELECT id, id_producto FROM compra";
$result_compras = $conn->query($sql_select_compras);

if ($result_compras->num_rows > 0) {
    while($compra = $result_compras->fetch_assoc()) {
        $compra_id = $compra['id'];
        $id_producto = $compra['id_producto'];

        // 4. OBTENER LA GANANCIA Y EL STOCK DEL PRODUCTO
        $sql_select_producto = "SELECT ganancia, stock FROM productos WHERE id_producto = $id_producto";
        $result_producto = $conn->query($sql_select_producto);
        
        if ($result_producto->num_rows > 0) {
            $producto = $result_producto->fetch_assoc();
            $ganancia_total_stock = (float)$producto['ganancia'];
            $stock_actual = (int)$producto['stock'];

            // 5. CALCULAR LA GANANCIA POR UNIDAD Y REDONDEAR A DOS DECIMALES
            $ganancia_por_unidad = 0.00; // Valor por defecto
            
            // Se realiza la división SÓLO si el stock es mayor que cero para evitar "División por Cero".
            if ($stock_actual > 0) {
                // Ganancia Unit. = Ganancia Total Stock / Stock Actual
                $ganancia_por_unidad = round($ganancia_total_stock / $stock_actual, 2); // Acomodado aquí
            }

            // 6. ACTUALIZAR EL REGISTRO DE COMPRA
            $sql_update = "UPDATE compra SET ganancia_unidad_vendida = ? WHERE id = ?";
            
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("di", $ganancia_por_unidad, $compra_id); 
            
            if ($stmt->execute()) {
                echo "Registro #{$compra_id}: Ganancia unitaria calculada en " . number_format($ganancia_por_unidad, 2) . " y actualizada.\n";
            } else {
                echo "Error al actualizar registro #{$compra_id}: " . $stmt->error . "\n";
            }
            $stmt->close();

        } else {
            echo "Advertencia: Producto con ID {$id_producto} no encontrado para la compra #{$compra_id}.\n";
        }
    }
} else {
    echo "No se encontraron registros en la tabla 'compra' para actualizar.\n";
}

$conn->close();
header("Location: venta.php");
?>