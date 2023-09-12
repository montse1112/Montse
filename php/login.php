<?php

$conn =mysqli_connect("localhost", "root", "montse", "decoh"); //decoh

if(!$conn){

    die("No hay conexion");
}

if(isset($_POST["ingresar"])){ //ingresar

    $correo =$_POST["correo"];
    $contrasena=$_POST["contrasena"];

    $query=mysqli_query($conn, "SELECT id, nombre, correo, contrasena FROM usuarios WHERE correo ='".$correo."' AND contrasena= '".$contrasena."' ");
    $nr=mysqli_num_rows($query);

    if($row = mysqli_fetch_array($query)){
        session_start();
        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['nombre_usuario'] = $row['nombre'];
        $_SESSION['correo']= $row['correo'];
    }

    if($nr==1){
 
        echo "<script> alert ('Bienvenido [$correo] '); location.href='../index.html'; </script>";
    }else{
        $correo =$_POST["correo"];
        $contrasena=$_POST["contrasena"];
    
        $query=mysqli_query($conn, "SELECT id, nombre, correo, contrasena FROM administrador WHERE correo ='".$correo."' AND contrasena= '".$contrasena."' ");
        $nr=mysqli_num_rows($query);

        
    if($row = mysqli_fetch_array($query)){
        session_start();
        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['nombre_usuario'] = $row['nombre'];
        $_SESSION['correo']= $row['correo'];
        
    }

    if($nr==1){
        echo "<script> alert ('Bienvenido administrador [$correo] '); location.href='../inicioAdm.html'; </script>";
    }else{
        echo "<script> alert ('Usuario o contraseña incorrectos'); location.href='../login.html'; </script>";
    }


    }
}

?>