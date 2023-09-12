<?php

include ("conexion.php");
$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

if(isset ($_POST["eliminarU"])){


    $id=$_POST["id"];


       $query1=mysqli_query($getconex, "SELECT id FROM usuarios WHERE id ='".$id."' ");
       $nr=mysqli_num_rows($query1);    
   
       if($nr==1){ 
   
           while ( $row = $query1->fetch_assoc() ) {
   
            $id=$row['id'];
   
           $query = "DELETE FROM usuarios WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "i", $id);
           mysqli_stmt_execute($sentencia);

   
           $afectado=mysqli_stmt_affected_rows($sentencia);
   
       if($afectado==1){
           echo "<script> alert ('El usuario se ha eliminado correctamente'); location.href='buscarU.php'; </script>";
   
       }else{
           echo "<script> alert ('El usuario no se ha eliminado correctamente'); location.href='buscarU.php'; </script>"; 
       }
   
       mysqli_stmt_close($sentencia);
       mysqli_close($getconex);
   }
       }

    }