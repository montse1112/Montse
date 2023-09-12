<?php

include ("conexion.php");
$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

if(isset ($_POST["actualizarU"])){

    $id=$_POST["id"];
    $nombre =$_POST["nombre"];
    $correo =$_POST["correo"];
    $telefono=$_POST["telefono"];
    $edad=$_POST["edad"];
    $genero =$_POST["genero"];
    $ciudad=$_POST["ciudad"];
    $contrasena=$_POST["contrasena"];


       $query1=mysqli_query($getconex, "SELECT id FROM usuarios WHERE id ='".$id."' ");
       $nr=mysqli_num_rows($query1);    
   
       if($nr==1){ 
   
           while ( $row = $query1->fetch_assoc() ) {
   
            $id=$row['id'];
   
           $query = "UPDATE usuarios SET nombre=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "si", $nombre, $id);
           mysqli_stmt_execute($sentencia);

           $query = "UPDATE usuarios SET correo=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "si", $correo, $id);
           mysqli_stmt_execute($sentencia);

           $query = "UPDATE usuarios SET telefono=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "si", $telefono, $id);
           mysqli_stmt_execute($sentencia);

           $query = "UPDATE usuarios SET edad=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "ii", $edad, $id);
           mysqli_stmt_execute($sentencia);

           $query = "UPDATE usuarios SET genero=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "si", $genero, $id);
           mysqli_stmt_execute($sentencia);

           $query = "UPDATE usuarios SET ciudad=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "si", $ciudad, $id);
           mysqli_stmt_execute($sentencia);

           $query = "UPDATE usuarios SET contrasena=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "si", $contrasena, $id);
           mysqli_stmt_execute($sentencia);
   
           $afectado=mysqli_stmt_affected_rows($sentencia);
   
       if($afectado==1){
           echo "<script> alert ('El usuario se ha actualizado correctamente'); location.href='buscarU.php'; </script>";
   
       }else{
           echo "<script> alert ('El usuario se ha actualizado correctamente'); location.href='buscarU.php'; </script>"; 
       }
   
       mysqli_stmt_close($sentencia);
       mysqli_close($getconex);
   }
       }

    }
 