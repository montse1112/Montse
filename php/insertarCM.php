<?php

include ("conexion.php");
$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

if(isset ($_POST["insertarCM"])){

    $distribuidor =$_POST["distribuidor"];
    $marca =$_POST["marca"];
    $color=$_POST["color"];
    $precio=$_POST["precio"];


    $query1=mysqli_query($getconex, "SELECT id, stock FROM cortinas_metalicas WHERE id_distribuidor ='".$distribuidor."' AND color= '".$color."' AND marca= '".$marca."' ");
    $nr=mysqli_num_rows($query1);    

    if($nr==1){ 

        while ( $row = $query1->fetch_assoc() ) {

        $id=$row['id'];
        $stock=$row['stock'];
        $stock=$stock+1;

        $query = "UPDATE cortinas_metalicas SET stock = ? WHERE id = ? ";
        $sentencia= mysqli_prepare($getconex, $query);
        mysqli_stmt_bind_param($sentencia, "ii", $stock, $id);
        mysqli_stmt_execute($sentencia);

        $afectado=mysqli_stmt_affected_rows($sentencia);

    if($afectado==1){
        echo "<script> alert ('El stock de cortina metalica se ha actualizado correctamente'); location.href='../agregarProductosCMet.html'; </script>";

    }else{
        echo "<script> alert ('El stock de cortina metalica no se ha actualizado correctamente'); location.href='../agregarProductosCMet.html'; </script>"; 
    }

    mysqli_stmt_close($sentencia);
    mysqli_close($getconex);
}
    }else{

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
                 if (move_uploaded_file($temp, '../images_CM/'.$archivo)) {
                    $ruta1= '../images_CM/'.$archivo;
                     //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                     chmod('../images_CM/'.$archivo, 0777);
                     //Mostramos el mensaje de que se ha subido co éxito
                     echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
                     //Mostramos la imagen subida
                     echo '<p><img src="../images_CM/'.$archivo.'"></p>';
                 }
                 else {
                    //Si no se ha podido subir la imagen, mostramos un mensaje de error
                    echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
                 }
               }
            }
         

    $stock =1;
    $query = "INSERT INTO cortinas_metalicas (id_distribuidor, marca, color, precio, stock, url_img) VALUES (?,?,?,?,?,?)";
    $sentencia= mysqli_prepare($getconex, $query);
    mysqli_stmt_bind_param($sentencia, "issdis", $distribuidor, $marca, $color, $precio, $stock, $ruta1);
    mysqli_stmt_execute($sentencia);
    $afectado=mysqli_stmt_affected_rows($sentencia);

    if($afectado==1){
        echo "<script> alert ('La cortina metalica se ha agregado correctamente'); location.href='../agregarProductosCMet.html'; </script>";

    }else{
        echo "<script> alert ('La cortina metalica no se ha agregado correctamente'); location.href='../agregarProductosCMet.html'; </script>"; 
    }

    mysqli_stmt_close($sentencia);
    mysqli_close($getconex);    
    }  
}

?>