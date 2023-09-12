<?php

include ("conexion.php");
$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

if(isset ($_POST["registrar"])){

    $nombre =$_POST["nombre"];
    $correo =$_POST["correo"];
    $telefono=$_POST["telefono"];
    $edad=$_POST["edad"];
    $genero =$_POST["genero"];
    $ciudad=$_POST["ciudad"];
    $contrasena=$_POST["contrasena"];

    $query = "INSERT INTO usuarios (nombre, correo, telefono, edad, genero, ciudad, contrasena) VALUES (?,?,?,?,?,?,?)";
    $sentencia= mysqli_prepare($getconex, $query);
    mysqli_stmt_bind_param($sentencia, "sssisss", $nombre, $correo, $telefono, $edad, $genero, $ciudad, $contrasena);
    mysqli_stmt_execute($sentencia);
    $afectado=mysqli_stmt_affected_rows($sentencia);


    if($afectado==1){
        echo "<script> alert ('El usuario [$nombre] se ha registrado correctamente'); location.href='../login.html'; </script>";

    
    }else{
        echo "<script> alert ('El usuario no se agrego correctamente'); location.href='../login.html'; </script>".mysqli_error($getconex); 
    }

    mysqli_stmt_close($sentencia);
    mysqli_close($getconex);
}

?>