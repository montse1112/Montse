<?php

function insertarImagen(){
    if(empty($_FILES[$tipo_imagen]["name"]))
    return;

    $file_name=$_FILES[$tipo_imagen]["name"];
    $extension=pathinfo($_FILES[$tipo_imagen]["name"], PATHINFO_EXTENSION);
    $ext_formatos = array('png', 'gif', 'jpg', 'jepg', 'pdf');

    if(!in_array(strtolower($extension), $ext_formatos))
    return;

    if($_FILES[$tipo_imagen]["size"] > 33000003008000)
    return;

    $dia = date("d");
    $mes =date("m");
    $anio= date("y");

    $targetDir = "img/$anio/$mes/$dia";

    @rmdir($targetDir);

    if(!file_exists($targetDir)){
        @mkdir($targetDir,077,true);
    }

    $token =md5(uniqid(rand()))
}
?>


$img=$_FILES['imagen']['name']; //obtiene el nombre
    $archivo=$_FILES['imagen']['tmp_name']; //contiene el archivo

    $ruta= 'images_CM/'.$img; //images_CM/nombre.jpg

    move_uploaded_file($archivo, $ruta);