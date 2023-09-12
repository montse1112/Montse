<?php

require_once '../libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class Pdf extends Dompdf{
    public function __contruct(){
        parent::__contruct();
    }
}
?>