<?php
 session_start();

include ("carritoGL.php");

$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

if($_POST){

    $SID = session_id();
    $total=0;
    $fecha=date("d-m-Y");
    $id=$_SESSION['id_usuario'];

    

    foreach($_SESSION['cart'] as  $key => $value){

        $total= $total + $value['cantidad'] * $value['precio'];
    }

    $query = "INSERT INTO ventas (fecha, monto_final, id_usuario) VALUES (?,?,?)";
    $sentencia= mysqli_prepare($getconex, $query);
    mysqli_stmt_bind_param($sentencia,"sdi", $fecha, $total, $id);
    mysqli_stmt_execute($sentencia);
    $afectado=mysqli_stmt_affected_rows($sentencia);

    $id_venta = mysqli_insert_id($getconex);
    $_SESSION['idV'] =$id_venta;

    if($afectado==1){

    foreach($_SESSION['cart'] as  $key => $value){

    $descargado=0;

    $query = "INSERT INTO detalles_ventasG (id_venta, id_producto, precio, cantidad, descargado) VALUES (?,?,?,?,?)";
    $sentencia= mysqli_prepare($getconex, $query);
    mysqli_stmt_bind_param($sentencia,"iidii", $id_venta,  $value['id'],  $value['precio'],  $value['cantidad'], $descargado);
    mysqli_stmt_execute($sentencia);
    $afectado1=mysqli_stmt_affected_rows($sentencia);



    }

      if($afectado==1){

        echo "<script> alert ('La compra se ha realizado correctamente'); location.href='pdf.php'; </script>";
       unset($_SESSION['cart']);
    }

    }else{
        echo "<script> alert ('La compra no se ha realizado correctamente'); location.href='carritoGL.php'; </script>"; 
    }
    
    unset($_SESSION['cart']);
    mysqli_stmt_close($sentencia);
    mysqli_close($getconex);  
}
?>