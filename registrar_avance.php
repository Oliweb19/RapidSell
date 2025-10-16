<?php
session_start();
include_once 'conexion_BD.php';
header('Content-Type: application/json');

$monto_bs = isset($_POST['montoAvance']) ? floatval($_POST['montoAvance']) : 0;
$metodo = isset($_POST['metodoPago']) ? $_POST['metodoPago'] : '';
$porcentaje = isset($_POST['porcentajeGanancia']) ? floatval($_POST['porcentajeGanancia']) : 0;
$dolar = isset($_SESSION['dolar']) ? floatval($_SESSION['dolar']) : 0;
$fecha = date('d/m/Y');
$total_bs = $monto_bs + ($monto_bs * $porcentaje / 100);
$total_dolar = $dolar > 0 ? round($total_bs / $dolar, 2) : 0;

// Validaciones básicas
if ($monto_bs <= 0) {
    echo json_encode(['success' => false, 'error' => 'Monto inválido.']);
    exit;
}
if ($dolar <= 0) {
    echo json_encode(['success' => false, 'error' => 'Tasa de dólar no definida.']);
    exit;
}

// Normalizar método
// aceptamos 'BS' o 'Pago Movil' (vienen desde el select)

// Comenzar transacción
mysqli_begin_transaction($conexion);
try {
    // 1) Asegurarnos que exista un producto "AVANCE" para referenciar en compra
    $prodQuery = mysqli_query($conexion, "SELECT id_producto FROM productos WHERE nombre_producto = 'AVANCE' LIMIT 1");
    if ($prodQuery && mysqli_num_rows($prodQuery) > 0) {
        $row = mysqli_fetch_assoc($prodQuery);
        $id_producto = intval($row['id_producto']);
    } else {
        // Insertar producto marcador
        $insProd = "INSERT INTO productos (cerial, nombre_producto, precio, precio_bs, ganancia, stock, estatus) VALUES ('AVANCE','AVANCE','0','0',0,0,0)";
        if (!mysqli_query($conexion, $insProd)) throw new Exception('No se pudo crear producto marcador: '.mysqli_error($conexion));
        $id_producto = mysqli_insert_id($conexion);
    }

    // 2) Generar id_compra único
    $fac = 'AVANCE-'.uniqid();

    // 3) Insertar en compra (usar nombre 'Avance' y cantidad 0)
    $nombre_compra = 'Avance';
    $cantidad_producto = 0;
    $stmtCompra = mysqli_prepare($conexion, "INSERT INTO compra (id_compra, id_producto, fecha_compra, nombre, cantidad_producto) VALUES (?, ?, ?, ?, ?)");
    if (!$stmtCompra) throw new Exception('Prepare compra falló: '.mysqli_error($conexion));
    // Tipos: s (string id_compra), i (int id_producto), s (string fecha), s (string nombre), i (int cantidad)
    mysqli_stmt_bind_param($stmtCompra, 'sissi', $fac, $id_producto, $fecha, $nombre_compra, $cantidad_producto);
    if (!mysqli_stmt_execute($stmtCompra)) throw new Exception('Insert compra falló: '.mysqli_error($conexion));
    mysqli_stmt_close($stmtCompra);

    // 4) Insertar en ventas
    $stmtVentas = mysqli_prepare($conexion, "INSERT INTO ventas (id_compra, total_pagar, total_pagar_bs, metodo_pago, fecha) VALUES (?, ?, ?, ?, ?)");
    if (!$stmtVentas) throw new Exception('Prepare ventas falló: '.mysqli_error($conexion));
    // total_pagar y total_pagar_bs en la BD son TEXT; guardamos como strings por seguridad
    $tp = strval($total_dolar);
    $tpbs = strval($total_bs);
    mysqli_stmt_bind_param($stmtVentas, 'sddss', $fac, $total_dolar, $total_bs, $metodo, $fecha);
    if (!mysqli_stmt_execute($stmtVentas)) throw new Exception('Insert ventas falló: '.mysqli_error($conexion));
    mysqli_stmt_close($stmtVentas);

    // 5) Insertar venta compensatoria para descontar del método opuesto
    // Si el avance fue en BS, vamos a restar el equivalente en Pago Movil (y viceversa)
    if (strtolower($metodo) === 'bs') {
        $metodo_opuesto = 'Pago Movil';
        $adj_total_dolar = -1 * $total_dolar;
        $adj_total_bs = -1 * $total_bs;
        $fac_adj = 'AJUSTE-'.uniqid();
    } else {
        // suponemos que cualquier otro método del formulario es Pago Movil
        $metodo_opuesto = 'BS';
        $adj_total_dolar = -1 * $total_dolar;
        $adj_total_bs = -1 * $total_bs;
        $fac_adj = 'AJUSTE-'.uniqid();
    }

    // Insertar compra para ajuste
    $nombre_compra_adj = 'Ajuste Avance';
    $cantidad_producto_adj = 0;
    $stmtCompraAdj = mysqli_prepare($conexion, "INSERT INTO compra (id_compra, id_producto, fecha_compra, nombre, cantidad_producto) VALUES (?, ?, ?, ?, ?)");
    if (!$stmtCompraAdj) throw new Exception('Prepare compra adj falló: '.mysqli_error($conexion));
    mysqli_stmt_bind_param($stmtCompraAdj, 'sisis', $fac_adj, $id_producto, $fecha, $nombre_compra_adj, $cantidad_producto_adj);
    if (!mysqli_stmt_execute($stmtCompraAdj)) throw new Exception('Insert compra adj falló: '.mysqli_error($conexion));
    mysqli_stmt_close($stmtCompraAdj);

    // Insertar venta adj con montos negativos
    $stmtVentasAdj = mysqli_prepare($conexion, "INSERT INTO ventas (id_compra, total_pagar, total_pagar_bs, metodo_pago, fecha) VALUES (?, ?, ?, ?, ?)");
    if (!$stmtVentasAdj) throw new Exception('Prepare ventas adj falló: '.mysqli_error($conexion));
    mysqli_stmt_bind_param($stmtVentasAdj, 'sddss', $fac_adj, $adj_total_dolar, $adj_total_bs, $metodo_opuesto, $fecha);
    if (!mysqli_stmt_execute($stmtVentasAdj)) throw new Exception('Insert ventas adj falló: '.mysqli_error($conexion));
    mysqli_stmt_close($stmtVentasAdj);

    mysqli_commit($conexion);
    echo json_encode(['success' => true, 'total_bs' => $total_bs, 'total_dolar' => $total_dolar]);
    header('Location:index.php');
    exit;
} catch (Exception $e) {
    mysqli_rollback($conexion);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    header('Location:index.php');
    exit;
}



?>

