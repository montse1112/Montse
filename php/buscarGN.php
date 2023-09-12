<?php

    function ejecuta_consulta($id)
    {
       //hacer la conexion a la base de datos
        include ("conexion.php");
        $getmysql = new mysqlconex();
        
        $getconex = $getmysql -> conex();

        //consulta de select
        $query1=mysqli_query($getconex, "SELECT id, id_distribuidor, marca, numero, pulgadas, color, precio, stock, url_img FROM globos_numero WHERE id ='$id'");

        $filas = array(); // Crea la variable $filas y se le asigna un array vacío
        // (Si la consulta no devuelve ningún resultado, la función por lo menos va a retornar un array vacío)

        $nr=mysqli_num_rows($query1);
        
        if($nr==1){ 
        while ($fila=mysqli_fetch_array($query1, MYSQLI_ASSOC)) {
            echo "<script> alert ('El globo se ha encontrado correctamente'); </script>";
            
            $filas[] = $fila; // Añade el array $fila al final de $filas
        }

        }else {
            echo "<script> alert ('No se ha encontrado el globo'); </script>";
        }

        mysqli_close($getconex);    

        return $filas; // Devuelve el array $filas
    }
?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="../styles.css">
    <title>Buscar Globos latex</title>
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
    <p class='t2 azulP'>Buscar globo de numero por ID</p>
    <p class='parrafo'>ID</p>
    <input type='text' placeholder='ID' name='buscar'>

    <br>
    <br>
    <input type='submit' name='buscarGN' value='Buscar globo de numero'>
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
                        <th>Distribuidor</th>
                        <th>Marca</th>
                        <th>Numero</th>
                        <th>Pulgadas</th>
                        <th>Color</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Imagen</th>    
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Si la variable $cm esta definida y no está vacía
                    if (isset($cm) && !empty($cm)) {
                        // Recorre cada $cm dentro del array $cortinaM
                        foreach ($cm as $cortinaM) {
                            $url=$cortinaM['url_img'];
                            $precio =$cortinaM['precio'];
                            $idGL=$cortinaM['id'];
                            ?>
                        <tr>
                            <td><?php echo $cortinaM['id'] ?></td>
                            <td><?php echo $cortinaM['id_distribuidor'] ?></td>
                            <td><?php echo $cortinaM['marca'] ?></td>
                            <td><?php echo $cortinaM['numero'] ?></td>
                            <td><?php echo $cortinaM['pulgadas'] ?></td>
                            <td><?php echo $cortinaM['color'] ?></td>
                            <td><?php echo "<form action='actualizarGN.php' method='POST' enctype='multipart/form-data'>";
                            echo "<input type='text' name='precio' value='$precio'> <br/> <br/>";
                            echo "<input type='hidden' name='id' value='$idGL'> <br/> <br/>";
                            ?></td>
                            <td><?php echo $cortinaM['stock'] ?></td>
                            <td><?php echo '<p><img src="../images_GN/'.$url.'"  height=200 width=250></p>';
                            echo "<input type='file' name='archivo' id='archivo'>"; ?></td>
                        </tr>

                    <?php

                        }
                    } ?>
                </tbody>
                </table>
        </div>

        <?php
                        echo "<br>";
                        echo "<center><input type='submit' name='actualizarGN' value='Actualizar globo latex' /></center>";
                        echo "</form>"; 

                        if (isset($cm) && !empty($cm)) {
                            // Recorre cada $cm dentro del array $cortinaM
                            foreach ($cm as $cortinaM) {
                                $idGL=$cortinaM['id'];
                                ?>
                            <tr>  
                                <td><?php echo "<form action='eliminarGN.php' method='POST' enctype='multipart/form-data'>";
                                echo "<input type='hidden' name='id' value='$idGL'> <br/> <br/>";
                                ?></td>
                            </tr>
    
                        <?php
    
                            }
                        } 
                        echo "<center><input type='submit' name='eliminarGN' value='Eliminar globo' /></center>";
                        echo "</form>";
                        ?>
    <?php
        } 
     ?>

</body>

</html>