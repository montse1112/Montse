<?php

$conn =mysqli_connect("localhost", "root", "montse", "decoh");

if(!$conn){

    die("No hay conexion");
}

if(isset($_POST["ingresarAdm"])){

    $correo =$_POST["correo"];
    $contrasena=$_POST["contrasena"];

    $query=mysqli_query($conn, "SELECT correo, contrasena FROM administrador WHERE correo ='".$correo."' AND contrasena= '".$contrasena."' ");
    $nr=mysqli_num_rows($query);

    if($nr==1){
        echo "<script> alert ('Bienvenido [$correo] '); location.href='../inicioAdm.html'; </script>";
    }else{
        echo "<script> alert ('Usuario o contraseña incorrectos'); location.href='../loginAdm.html'; </script>";
    }
}

?>