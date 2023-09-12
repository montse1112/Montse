
<?php
session_start();

 //hacer la conexion a la base de datos
 include ("conexion.php");
 include ("login.php");

 $getmysql = new mysqlconex();
 
 $getconex = $getmysql -> conex();

 
 if(isset ($_POST["agregarCarrito"])){

    if(isset ($_POST["cart"])){
        
        $session_array = array_column($_SESSION['cart'], "id");
        
        if(!in_array($_GET['id'], $session_array_id)){

            $session_array = array(
                'id' => $_GET['id'],
                "marca" => $_POST['marca'],
                "color" => $_POST['color'],
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
    <title>Carrito de Cortinas metalicas</title>
</head>

<body>

<header>
        <h1>Gobos DecoH</h1>
        <div class="carrito">
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

    <h2>CORTINAS METALICAS</h2>
    <br>

<?php

       $id_usuario=$_SESSION['id_usuario'];
       $nombre_usuario=$_SESSION['nombre_usuario'];
       ?>
       <?php
        //consulta de select
        $query1=mysqli_query($getconex, "SELECT * FROM cortinas_metalicas");


        $nr=mysqli_num_rows($query1);
        
        while($fila=mysqli_fetch_array($query1, MYSQLI_ASSOC)) { ?>

        <div class="center">
        <section class="containerP">
            
        <article class="card">
                <?php $url=$fila['url_img']; ?>

        <form method="POST" action="CarritoCM.php?id=<?=$fila['id']?>">

        <?php echo '<p><img src="'.$url.'"  height=200 width=220></p>';?>
        <h5><input type='hidden' name='color' value=<?= $fila['color']?> ></h5>
        <p>Marca: <?= $fila['marca']?> </p>
        <p>Color: <?= $fila['color']?> </p>
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
        <div>

            <?php 

            $total=0;
            $fecha=date("d-m-Y");
            $id=11;

            $output="";

            $output.="
            
            <table>    
        <tr>
        <th>Id</th>
        <th>Marca</th>
        <th>Color</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Precio Total</th>
        <th>Accion</th>
        </tr>";

        if(!empty($_SESSION['cart'])){

            foreach($_SESSION['cart'] as  $key => $value){

                $output .="
                <br>
                <tr>
                <td>".$value['id']."</td>
                <td>".$value['marca']."</td>
                <td>".$value['color']."</td>
                <td>".$value['precio']."</td> 
                <td>".$value['cantidad']."</td> 
                <td>$".number_format($value['precio']* $value['cantidad'],2 )."</td>

                <td>
                <a href='CarritoCM.php?action=remove&id=".$value['id']."'>

                <button> Remover</button>
                </a>

                ";

                $total= $total + $value['cantidad'] * $value['precio'];
            }
            

            $output .="
            <tr>
            <td colspan='4'></td>
            <td></b>Total a pagar </b></td>
            <td>".number_format($total,2)."</td>
            <td>
            <a href='CarritoCM.php?action=limpiarTodo'>
            <button>Limpiar todo</button>
            </a>
            <tr>

            </table>

            <br>
            <center><form method=POST action=comprarCarritoCM.php>
            <input type='hidden' name='fecha' value=$fecha>
            <input type='hidden' name='total' value=".number_format($total,2).">
            <input type='hidden' name='id' value=$id>
            <input type='submit' class= 'botonC' name='comprarCarrito' value='COMPRAR'/>
            </form></center>

            ";
            
        }

        echo $output;
        
            ?>
        </div>

        <?php

        if(isset($_GET['action'])){

            if($_GET['action']== "limpiarTodo"){
                unset($_SESSION['cart']);
                echo "<script> alert ('Limpiando todo'); location.href='CarritoCM.php'; </script>";
            }

            if($_GET['action'] == "remove"){
                 foreach($_SESSION['cart'] as $key => $value){
                    if($value['id']== $_GET['id']){
                        unset($_SESSION['cart'][$key]);
                        echo "<script> alert ('El producto se removio con exito del carrito'); location.href='CarritoCM.php'; </script>";
                    }
                 }
            }
    }
        ?>
 

</body>
</html>