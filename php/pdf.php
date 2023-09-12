<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

//Load Composer's autoloader
//require 'vendor/autoload.php';

include ("conexion.php");
$getmysql = new mysqlconex();

$getconex = $getmysql -> conex();

$id_venta=0;
$id_venta =$_SESSION['idV'];
$correoU = $_SESSION['correo'];
$nombreU =$_SESSION['nombre_usuario'];
$fecha=date("d-m-Y");


function fetch_customer_data($getconex, $id_venta){

    $query = "SELECT * FROM detalles_ventasg WHERE id_venta='".$id_venta."'";
    $stmt = $getconex->prepare($query);
    $stmt->execute();
    
    $resultSet = $stmt->get_result();
    $data = $resultSet->fetch_all(MYSQLI_ASSOC);

    $output = "";

    $output .="

    <table>    
    <tr>
    <th>Marca</th>
    <th>Color</th>
    <th>Pulgadas</th>
    <th>Cantidad globos</th>
    <th>Precio</th>
    <th>Cantidad</th>
    <th>Total</th>
    </tr>";

    $total1=0;
        foreach ( $data as $row) {

            $precio=$row['precio'];
            $cant=$row['cantidad'];

            //---------------------------------------CONSULTA PARA COLOR DEL GLOBO Y MARCA
            $query1 = "SELECT * FROM globoslatex WHERE id='".$row['id_producto']."'";
            $stmt1 = $getconex->prepare($query1);
            $stmt1->execute();
            
            $resultSet1 = $stmt1->get_result();
            $data1 = $resultSet1->fetch_all(MYSQLI_ASSOC);
            
            
            $total1= $total1 + $row['cantidad'] * $row['precio'];

            $total=$precio*$cant;
            

            foreach ( $data1 as $row1) {
            
                //$url=$row1['url_img'];

            $output .= "
            <tr>
           
            <td>".$row1['marca']."</td>
            <td>".$row1['color']."</td>
            <td>".$row1['pulgadas']."</td>
            <td>".$row1['cantidad_globos']."</td>
            <td>$".$row['precio']."</td>
            <td>".$row['cantidad']."</td>
            <td>$".$total."</td>
            </tr>
            ";

            }
            //------------------------------------------------

           
        }

        $output .= "

        <tr>
        <td colspan='5'></td>
        <td></b>Total a pagar</b></td>
        <td>$".number_format($total1,2)."</td>
        <td>
        </table>";
    

    return $output;
}

$message=" ";
if(isset($_POST['action'])){

    include('pdf_generar.php');
    $file_name =md5(rand()) . '.pdf';

    $html_code='<style> 
    table {
        width: 90%;
        margin: 0 auto;
        border-collapse: collapse;
    }
    
    th, td {
    
        border: solid 1px #eee;
        padding: 5px;
    }
    
    th {
        background-color: black;
        color: white;
        text-align: left;
    }
    tr:nth-child(even) {
        background-color: #eee;
    }
    </style>';

    $html_code.= '<h1>Detalles de compra<h1>
    <p>Fecha: '.$fecha.'</p>
    <p>ID Venta: '.$id_venta.'</p>
    <p>Usuario: '.$nombreU.'</p>
    <p>Correo: '.$correoU.'</p>';

    $html_code.= fetch_customer_data($getconex, $id_venta);
    $html_code.='<br><br><p>¡DECOH AGRADECE TU COMPRA!</p>';

    $pdf= new Pdf();
    $pdf -> load_html($html_code);
    $pdf -> render();
    $file= $pdf->output();

    file_put_contents($file_name, $file);

    $mail= new PHPMailer(true);
    $mail -> IsSMTP();
    $mail -> Host='smtp.gmail.com'; 
    $mail -> Port=587;
    $mail -> SMTPAuth = true;
    $mail -> Username ='decohglobos@gmail.com'; //Para acceder al correo
    $mail -> Password ='jezsnhpirvbjxsle';
    $mail -> SMTPSecure = 'tls';

     //Recipients
     $mail->setFrom('decohglobos@gmail.com', 'DecoH'); //Desde donde se envia
     $mail->addAddress($correoU);     //A quien se le enviara

     $mail-> WordWrap=50;
     $mail-> IsHTML(true);
     $mail->addAttachment($file_name);

     //Content
    $mail->Subject = 'Detalles de compra';
    $mail->Body    = 'Los detalles se encuentran en el pdf';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if($mail-> Send()){
        $message= "<script> alert ('El PDF se ha enviado con exito, revisa tu correo'); location.href='carritoGL.php'; </script>";
    }

    unlink($file_name);

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
    </header>

    <h2>Detalles de carrito de compras de globos de latex</h2>
    <br>

    <form method="post">
        <center><input type="submit" class="botonC" name="action" value="Enviar PDF de datos de compra a mi correo" /><center>
        <br>
        <?php
        echo $message;
        ?>
        
        <br>
        <br>
    </form>

        <?php 
        echo fetch_customer_data($getconex, $id_venta);
        ?>
</body>
</html>

