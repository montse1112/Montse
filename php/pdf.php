<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../vendor/autoload.php";
require('../fpdf185/fpdf.php');
require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/SMTP.php');
require('../PHPMailer/src/Exception.php');

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



$query=mysqli_query($getconex, "SELECT * FROM detalles_ventasg WHERE id_venta='".$id_venta."'");
$resultado_carrito=mysqli_num_rows($query);
//$resultado_carrito = $con->query($query);


function fetch_customer_data($getconex, $id_venta){

    $query = "SELECT * FROM detalles_ventasg WHERE id_venta='".$id_venta."'";
    //$resultado_carrito = $con->query($query);
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

    
        
    // Crear un nuevo objeto FPDF
    $pdf = new FPDF();

    // Agregar una nueva página al PDF
    $pdf->AddPage();

    // Generar el contenido del PDF
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, 'Este mensaje ha sido enviado por Decoh', 0, 1);
    $pdf->Ln(10); // Cambio de ln a Ln
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Usuario: ' . $nombreU, 0, 1); // Cambio de $datos_usuario['nombre'] a $nombre
    $pdf->Cell(0, 10, 'Correo: ' . $correoU, 0, 1); // Cambio de $datos_usuario['telefono'] a $telefono
    $fecha = date('l jS \of F Y h:i:s A');
    $pdf->Cell(0, 10, 'Fecha: ' . $fecha, 0, 1);
    $pdf->Cell(0, 10, 'Datos de compra:', 0, 1);

    $total=0;
    
    if ($resultado_carrito==1) {
        while ($fila_carrito = mysqli_fetch_assoc($query)) {
            // Obtener los datos específicos del carrito
            $venta = $fila_carrito['id_venta'];
            // Otros campos del carrito
            $id_producto = $fila_carrito['id_producto'];
            $precio = $fila_carrito['precio'];
            $cantidad = $fila_carrito['cantidad'];
            
            $total=$total+$precio;

            // Agregar los datos del carrito al PDF
            $pdf->Cell(0, 10, "\nId venta: $venta", 0, 1);
            $pdf->Cell(0, 10, "\nId producto: $id_producto", 0, 1);
            $pdf->Cell(0, 10, "\nPrecio: $precio", 0, 1);
            $pdf->Cell(0, 10, "\nCantidad de bolsas: $cantidad", 0, 1);
            $pdf->Cell(0, 10, 'Total: $' . $total, 0, 1); 
            
        }
    }
    
    

        
  // Guardar el PDF en el servidor
$pdfPath = '../pdf/orden'.$nombreU.'.pdf';
$pdf->Output($pdfPath, 'F');

$message=" ";
if(isset($_POST['action'])){

    
    // Definir los encabezados del correo electrónico
    $mail = new PHPMailer();
	$mail->CharSet = 'utf-8';
	$mail->Host = "smtp.gmail.com";
	$mail->From = "decohglobos@gmail.com";
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Username = "decohglobos@gmail.com";
	$mail->Password = "jezsnhpirvbjxsle";
	$mail->Port = 587;
	$mail->AddAddress($correoU);
	$mail->SMTPDebug = 0;   //Muestra las trazas del mail, 0 para ocultarla
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = 'Gracias por la Compra!';
	$mail->Body = '<b>Este es el recibo de tu compra:)</b>';
	$mail->AltBody = 'Hemos enviado el recibo';

	$inMailFileName = "recibo.pdf";
	$filePath = "../pdf/orden" .$nombreU.".pdf" ;
	$mail->AddAttachment($filePath, $inMailFileName);

	if($mail-> Send()){
        $message= "<script> alert ('El PDF se ha enviado con exito, revisa tu correo'); location.href='carritoGL.php'; </script>";
    }
}

   /* include('pdf_generar.php');
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

    unlink($file_name);*/



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

