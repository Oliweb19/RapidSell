<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba</title>
</head>
<body>
    <?php
    date_default_timezone_set('America/Caracas');
    $fecha_hoy = date('d/m/Y');
    include_once 'conexion_BD.php';

    // Calcular total diario por método de pago
    $sql_total = "SELECT metodo_pago, SUM(total_pagar) AS total FROM ventas WHERE fecha = '$fecha_hoy' GROUP BY metodo_pago";
    $res_total = mysqli_query($conexion, $sql_total);

    // Calcular ganancia diaria solo productos activos
    $sql_ganancia = "SELECT SUM(p.ganancia * c.cantidad_producto) AS ganancia_hoy
        FROM compra c
        JOIN productos p ON c.id_producto = p.id_producto
        WHERE c.fecha_compra = '$fecha_hoy' AND p.estatus = 1";
    $res_ganancia = mysqli_query($conexion, $sql_ganancia);
    $ganancia_hoy = 0;
    if($row = mysqli_fetch_assoc($res_ganancia)){
        $ganancia_hoy = $row['ganancia_hoy'] ? $row['ganancia_hoy'] : 0;
    }
?>
<div class="montos-diarios" style="margin: 20px 0;">
    <h2>Montos Diarios</h2>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f3f3f3;">
                <th style="padding:8px 0;">Metodo de pago</th>
                <th style="padding:8px 0;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_dia = 0;
            while($row = mysqli_fetch_assoc($res_total)){
                $total_dia += $row['total'];
                echo '<tr><td style="text-align:center;">'. $row['metodo_pago'] .'</td><td style="text-align:center;">'. number_format($row['total'],2) .'$</td></tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td style="font-weight:bold;font-size:1.2em;text-align:right;padding:10px;">Total: <?php echo number_format($total_dia,2); ?>$</td>
                <td style="font-weight:bold;font-size:1.2em;text-align:left;padding:10px;">Ganancia: <?php echo number_format($ganancia_hoy,2); ?>$</td>
            </tr>
        </tfoot>
    </table>
</div>
</body>
</html>