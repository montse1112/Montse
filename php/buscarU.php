<?php

    function ejecuta_consulta($id)
    {
       //hacer la conexion a la base de datos
        include ("conexion.php");
        $getmysql = new mysqlconex();
        
        $getconex = $getmysql -> conex();

        //consulta de select
        $query1=mysqli_query($getconex, "SELECT id, nombre, correo, telefono, edad, genero, ciudad, contrasena FROM usuarios WHERE id ='$id'");

        $filas = array(); // Crea la variable $filas y se le asigna un array vacío
        // (Si la consulta no devuelve ningún resultado, la función por lo menos va a retornar un array vacío)

        $nr=mysqli_num_rows($query1);
        
        if($nr==1){ 
        while ($fila=mysqli_fetch_array($query1, MYSQLI_ASSOC)) {
            echo "<script> alert ('El usuario se ha encontrado correctamente'); </script>";
            
            $filas[] = $fila; // Añade el array $fila al final de $filas
        }

        }else {
            echo "<script> alert ('No se ha encontrado al usuario'); </script>";
        }

        mysqli_close($getconex);    

        return $filas; // Devuelve el array $filas
    }
?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="styles.css?v=<?php echo(rand()); ?>" /> 
<script src="/js/mi_script.js?v=<?php echo(rand()); ?>"></script> 
    <title>Editar Usuarios</title>
</head>

<body>

<header>
        <h1>Gobos DecoH</h1>
    </header>

    <nav class="barra">
        <ul>
            <center><li><a href="../inicioAdm.html">Inicio</a></li></center>
        </ul>
    </nav>

    <br>

    <?php
    

    $mipag=$_SERVER["PHP_SELF"];
    
    echo("<article class='boxRegistro'>
    <form action='". $mipag . "' method='POST' class='formulario_login'>

    <br>
    <p class='t2 azulP'>Buscar usuario por ID</p>
    <p class='parrafo'>ID</p>
    <input type='text' placeholder='ID' name='buscar'>

    <br>
    <br>
    <input type='submit' name='buscarU' value='Buscar usuario'>
    </form>

    </article>
    <br>");

    ?>

    <?php

        $mibusqueda=$_POST["buscar"] ?? null;

        $mipag=$_SERVER["PHP_SELF"];

        if ($mibusqueda!=null) {
            $cm = ejecuta_consulta($mibusqueda);
    ?>

        <div id="main-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Edad</th>
                        <th>Genero</th>
                        <th>Ciudad</th>
                        <th>Contrasena</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Si la variable $cm esta definida y no está vacía
                    if (isset($cm) && !empty($cm)) {
                        // Recorre cada $cm dentro del array $cortinaM
                        foreach ($cm as $cortinaM) {

                            $idU =$cortinaM['id'];
                            $nombre =$cortinaM['nombre'];
                            $correo =$cortinaM['correo'];
                            $telefono=$cortinaM['telefono'];
                            $edad=$cortinaM['edad'];
                            $genero =$cortinaM['genero'];
                            $ciudad=$cortinaM['ciudad'];
                            $contrasena=$cortinaM['contrasena'];
                            ?>
                        <tr>
                            <td><?php echo $cortinaM['id'] ?></td>
                            <td><?php echo "<form action='actualizarU.php' method='POST' enctype='multipart/form-data'>";
                            echo "<input type='text' name='nombre' value='$nombre'> <br/> <br/>";
                            echo "<input type='hidden' name='id' value='$idU'> <br/> <br/>";
                            ?></td>
                            <td><?php echo "<input type='text' name='correo' value='$correo'>" ;?></td>
                            <td><?php echo "<input type='text' name='telefono' value='$telefono'>";?></td>
                            <td><?php echo "<input type='text' name='edad' value='$edad'>"; ?></td>
                            <td><?php echo "<input type='text' name='genero' value='$genero'>"; ?></td>
                            <td><?php echo "<input type='text' name='ciudad' value='$ciudad'>"; ?></td>
                            <td><?php echo "<input type='text' name='contrasena' value='$contrasena'>"; ?></td>
                        </tr>

                    <?php

                        }
                    } ?>
                </tbody>
                </table>
        </div>

        <?php
                        echo "<br>";
                        echo "<center><input type='submit' name='actualizarU' value='Actualizar usuario' /></center>";
                        echo "</form>"; 

                        if (isset($cm) && !empty($cm)) {
                            // Recorre cada $cm dentro del array $cortinaM
                            foreach ($cm as $cortinaM) {
                                $idU=$cortinaM['id'];
                                ?>
                            <tr>  
                                <td><?php echo "<form action='eliminarU.php' method='POST' enctype='multipart/form-data'>";
                                echo "<input type='hidden' name='id' value='$idU'> <br/> <br/>";
                                ?></td>
                            </tr>
    
                        <?php
    
                            }
                        } 
                        echo "<center><input type='submit' name='eliminarU' value='Eliminar usuario' /></center>";
                        echo "</form>";
                        ?>
    <?php
        } 
     ?>

</body>

</html>