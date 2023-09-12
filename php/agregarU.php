<?php

include ("conexion.php");
$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

if(isset ($_POST["agregarU"])){

    $nombre =$_POST["nombre"];
    $correo =$_POST["correo"];
    $telefono=$_POST["telefono"];
    $edad=$_POST["edad"];
    $genero =$_POST["genero"];
    $ciudad=$_POST["ciudad"];
    $contrasena=$_POST["contrasena"];


    $query1=mysqli_query($getconex, "SELECT id FROM usuarios WHERE correo ='".$correo."' AND contrasena= '".$contrasena."' ");
    $nr=mysqli_num_rows($query1);    

    if($nr==1){ 
 
        echo "<script> alert ('Ya existe un usuario con ese correo y contrasena'); location.href='../agregarUsuariosAdm.html'; </script>";
   

    mysqli_stmt_close($sentencia);
    mysqli_close($getconex);
}

    $query = "INSERT INTO usuarios (nombre, correo, telefono, edad, genero, ciudad, contrasena) VALUES (?,?,?,?,?,?,?)";
    $sentencia= mysqli_prepare($getconex, $query);
    mysqli_stmt_bind_param($sentencia, "sssisss", $nombre, $correo, $telefono, $edad, $genero, $ciudad, $contrasena);
    mysqli_stmt_execute($sentencia);
    $afectado=mysqli_stmt_affected_rows($sentencia);

    if($afectado==1){
        echo "<script> alert ('El usuario se ha agregado correctamente'); location.href='../agregarUsuariosAdm.html'; </script>";

    }else{
        echo "<script> alert ('El usuario no se ha agregado correctamente'); location.href='../agregarUsuariosAdm.html'; </script>"; 
    }

    mysqli_stmt_close($sentencia);
    mysqli_close($getconex);    
    }  


?>