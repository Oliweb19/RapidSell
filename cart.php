<?php 
    session_start(); 

	include_once 'conexion_BD.php';

	$id = $_POST['id'];
	$cantidad=$_POST['cant'];

	$sql = "SELECT * FROM productos WHERE id_producto = '$id'";
    $resultado = mysqli_query($conexion,$sql);
	$resultado = mysqli_fetch_array($resultado);

	if($cantidad > $resultado['stock']){
		echo "<script> window.alert('La cantidad sobrepasa el stock'); window.location='venta.php';</script>"; 	
	}
	else{
		if(isset($_SESSION['carrito'])){
			$carrito_mio=$_SESSION['carrito'];
			if(isset($_POST['producto'])){
				$titulo=$_POST['producto'];
				$precio=$_POST['precio'];
				$precio_bs=$_POST['precio_bs'];
				$cantidad=$_POST['cant'];
				$idd = $_POST['id'];
				$num=0;
				$total = $precio * $cantidad;
				$total1 = $precio_bs * $cantidad;
				$carrito_mio[]=array("id"=> $idd,"titulo"=>$titulo,"precio"=>$precio,"precio_bs"=>$precio_bs,"cantidad"=>$cantidad,"total"=>$total,"total1"=>$total1);
			}
		}
		else{
			$titulo=$_POST['producto'];
			$precio=$_POST['precio'];
			$precio_bs=$_POST['precio_bs'];
			$cantidad=$_POST['cant'];
			$idd = $_POST['id'];
			$total = $precio * $cantidad;
			$total1 = $precio_bs * $cantidad;
			$carrito_mio[]=array("id"=> $idd,"titulo"=>$titulo,"precio"=>$precio,"precio_bs"=>$precio_bs,"cantidad"=>$cantidad,"total"=>$total,"total1"=>$total1);	
		}

		$_SESSION['carrito']=$carrito_mio;

    	header("Location: ".$_SERVER['HTTP_REFERER']."");
	}

    
?>



