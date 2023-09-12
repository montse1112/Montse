<?php
session_start();

 //hacer la conexion a la base de datos
 include ("conexion.php");
 include ("login.php");

 $getmysql = new mysqlconex();
 
 $getconex = $getmysql -> conex();


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
    </header>

    <nav class="barra">
        <ul>
            <center><li><a href="../productos.html">Productos</a></li></center>
        </ul>
    </nav>

    <h2>Detalles de carrito de compras de globos de latex</h2>
    <br>

    <center><form method="get" action="carritoGL.php"><button type="submit">REGRESAR PARA SEGUIR COMPRANDO</button></form><center>

    <br>
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
        <th>Pulgadas</th>
        <th>Cantidad globos</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Precio Total</th>
        <th>Accion</th>
        </tr>";

        if(!empty($_SESSION['cart'])){

            foreach($_SESSION['cart'] as  $key => $value){

                $total= $total + $value['cantidad'] * $value['precio'];

                $output .="
                <br>
                <tr>
                <td>".$value['id']."</td>
                <td>".$value['marca']."</td>
                <td>".$value['color']."</td>
                <td>".$value['pulgadas']."</td>
                <td>".$value['cant_globos']."</td>
                <td>".$value['precio']."</td> 
                <td>".$value['cantidad']."</td> 
                <td>$".number_format($value['precio']* $value['cantidad'],2 )."</td>

                <td>
                <a href='detallesCarritoGL.php?action=remove&id=".$value['id']."'>

                <button> Remover</button>
                </a>

                ";

                
            }
            

            $output .="
            <tr>
            <td colspan='6'></td>
            <td></b>Total a pagar </b></td>
            <td>".number_format($total,2)."</td>
            <td>
            <a href='detallesCarritoGL.php?action=limpiarTodo'>
            <button>Limpiar todo</button>
            </a>
            <tr>

            </table>

            <br>
            <center><form method=POST action=comprarCarritoGL.php>
            <input type='hidden' name='fecha' value=$fecha>
            <input type='hidden' name='total' value=".number_format($total,2).">
            <input type='hidden' name='id' value=$id>
            <input type='submit'  class='botonC' name='comprarCarrito' value='COMPRAR'/>
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
                echo "<script> alert ('Limpiando todo'); location.href='detallesCarritoGL.php'; </script>";
            }

            if($_GET['action'] == "remove"){
                 foreach($_SESSION['cart'] as $key => $value){
                    if($value['id']== $_GET['id']){
                        unset($_SESSION['cart'][$key]);
                        echo "<script> alert ('El producto se removio con exito del carrito'); location.href='detallesCarritoGL.php'; </script>";
                    }
                 }
            }
    }
        ?>

</body>
</html>