<?php
session_start();

 //hacer la conexion a la base de datos
 include ("conexion.php");
 include ("login.php");

 $getmysql = new mysqlconex();
 
 $getconex = $getmysql -> conex();

 
 if(isset ($_POST["agregarCarrito"])){

    echo "<script> alert ('El producto se agrego al carrito'); location.href='carritoGL.php'; </script>";

    if(isset ($_POST["cart"])){
        
        $session_array = array_column($_SESSION['cart'], "id");
        
        if(!in_array($_GET['id'], $session_array_id)){

            $session_array = array(
                'id' => $_GET['id'],
                "marca" => $_POST['marca'],
                "color" => $_POST['color'],
                "pulgadas" => $_POST['pulgadas'],
                "cant_globos" => $_POST['cantidad_globos'],
                "precio" => $_POST['precio'],
                "cantidad" => $_POST['cantidad'],
            );
    
            $_SESSION['cart'][] = $session_array;

        }
    }else {
        $session_array = array(
            'id' => $_GET['id'],
            "marca" => $_POST['marca'],
            "color" => $_POST['color'],
            "pulgadas" => $_POST['pulgadas'],
            "cant_globos" => $_POST['cantidad_globos'],
            "precio" => $_POST['precio'],
            "cantidad" => $_POST['cantidad'],
        );

        $_SESSION['cart'][] = $session_array;
    }

 }
?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="styles.css?v=<?php echo(rand()); ?>" /> 
<script src="/js/mi_script.js?v=<?php echo(rand()); ?>"></script> 
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimun-scale=1.0">
    <title>Carrito de Cortinas metalicas</title>
</head>

<body>

<header>
        <h1>Gobos DecoH</h1>
         <div class="carrito">
          <a href="detallesCarritoGL.php">
            <i class="fa fa-shopping-cart"></i>
            <span>Mi carrito</span>
          </a>
       

    <?php
       $id_usuario=$_SESSION['id_usuario'];
       $nombre_usuario=$_SESSION['nombre_usuario'];
       ?>
        <span>Usuario: <?= $nombre_usuario?></span>
</div>
    </header>

    <nav class="barra">
        <ul>
            <center><li><a href="../productos.html">Productos</a></li></center>
        </ul>
       
    </nav>

    <h2>Globos Latex</h2>
<?php

       $id_usuario=$_SESSION['id_usuario'];
       $nombre_usuario=$_SESSION['nombre_usuario'];
       ?>
       <?php
        //consulta de select
        $query1=mysqli_query($getconex, "SELECT * FROM globoslatex");


        $nr=mysqli_num_rows($query1);
        
        while($fila=mysqli_fetch_array($query1, MYSQLI_ASSOC)) { ?>

        <div class="center">
        <section class="containerP">
            
        <article class="card">
                <?php $url=$fila['url_img']; ?>

                
        <form method="POST" action="carritoGL.php?id=<?=$fila['id']?>">

        <?php echo '<p><img src="'.$url.'"  height=200 width=220></p>';?>
        <h5><input type='hidden' name='color' value=<?= $fila['color']?> ></h5>
        <h5><input type='hidden' name='pulgadas' value=<?= $fila['pulgadas']?> ></h5>
        <h5><input type='hidden' name='cantidad_globos' value=<?= $fila['cantidad_globos']?> ></h5>
        <p>Marca: <?= $fila['marca']?> </p>
        <p>Color: <?= $fila['color']?> </p>
        <p>Pulgadas: <?= $fila['pulgadas']?> </p>
        <p>Cantidad globos: <?= $fila['cantidad_globos']?> </p>
        <span class="price">$<?= $fila['precio']?></span>
        <h5><input type='hidden' name='marca' value=<?= $fila['marca']?> ></h5>
        <h5><input type='hidden' name='precio' value=<?= $fila['precio']?> ></h5>
        <p>Cantidad: </p>
        <h5><input type='number' name='cantidad' value='1' ></h5>
        <input type='submit' class="botonC" name='agregarCarrito' value='Agregar al carrito' />
        </form>
        </div>
            
        <?php }

        ?>
        
        </article>
        </section>

</body>
</html>