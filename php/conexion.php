<?php 

class mysqlconex
{
    public function conex(){
      $enlace = mysqli_connect("localhost", "root", "montse", "decoh");  

      return $enlace;
    }
}
    
?>