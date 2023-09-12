<?php

include ("conexion.php");
$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

if(isset ($_POST["actualizarGL"])){

    $precio=$_POST["precio"];
    $id=$_POST["id"];


    //Volver a obtener la ruta de la cortina metalica
    //Recogemos el archivo enviado por el formulario
    $archivo = $_FILES['archivo']['name'];
    //Si el archivo contiene algo y es diferente de vacio
    if (isset($archivo) && $archivo != "") {
       //Obtenemos algunos datos necesarios sobre el archivo
       $tipo = $_FILES['archivo']['type'];
       $tamano = $_FILES['archivo']['size'];
       $temp = $_FILES['archivo']['tmp_name'];
       //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
      if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
         echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
         - Se permiten archivos .gif, .jpg, .png. y de 200 kb como máximo.</b></div>';
      }
      else {
         //Si la imagen es correcta en tamaño y tipo
         //Se intenta subir al servidor
         if (move_uploaded_file($temp, '../images_GL/'.$archivo)) {
            $ruta1= '../images_GL/'.$archivo;
             //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
             chmod('../images_GL/'.$archivo, 0777);
             //Mostramos el mensaje de que se ha subido co éxito
             echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
             //Mostramos la imagen subida
             echo '<p><img src="../images_GL/'.$archivo.'"></p>';
         }
         else {
            //Si no se ha podido subir la imagen, mostramos un mensaje de error
            echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
         }
       }

       $query1=mysqli_query($getconex, "SELECT id, precio, url_img FROM globoslatex WHERE id ='".$id."' ");
       $nr=mysqli_num_rows($query1);    
   
       if($nr==1){ 
   
           while ( $row = $query1->fetch_assoc() ) {
   
            $id=$row['id'];
   
           $query = "UPDATE globoslatex SET precio=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "di", $precio, $id);
           mysqli_stmt_execute($sentencia);

           $query = "UPDATE globoslatex SET url_img=? WHERE id = ? ";
           $sentencia= mysqli_prepare($getconex, $query);
           mysqli_stmt_bind_param($sentencia, "si", $ruta1, $id);
           mysqli_stmt_execute($sentencia);
   
           $afectado=mysqli_stmt_affected_rows($sentencia);
   
       if($afectado==1){
           echo "<script> alert ('El globo se ha actualizado correctamente'); location.href='buscarGL.php'; </script>";
   
       }else{
           echo "<script> alert ('El globo no se ha actualizado correctamente'); location.href='buscarGL.php'; </script>"; 
       }
   
       mysqli_stmt_close($sentencia);
       mysqli_close($getconex);
   }
    }

    }else{

        $query1=mysqli_query($getconex, "SELECT id, precio FROM globoslatex WHERE id ='".$id."' ");
        $nr=mysqli_num_rows($query1);    
    
        if($nr==1){ 
    
            while ( $row = $query1->fetch_assoc() ) {
    
             $id=$row['id'];
    
            $query = "UPDATE globoslatex SET precio=? WHERE id = ? ";
            $sentencia= mysqli_prepare($getconex, $query);
            mysqli_stmt_bind_param($sentencia, "di", $precio, $id);
            mysqli_stmt_execute($sentencia);

            $afectado=mysqli_stmt_affected_rows($sentencia);
    
        if($afectado==1){
            echo "<script> alert ('El globo se ha actualizado correctamente'); location.href='buscarGL.php'; </script>";
    
        }else{
            echo "<script> alert ('El globo no se ha actualizado correctamente'); location.href='buscarGL.php'; </script>"; 
        }
    
        mysqli_stmt_close($sentencia);
        mysqli_close($getconex);

       }

    }
 
}
}