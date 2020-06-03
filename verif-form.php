 
<?php
 
//connection_database.php
 
$connect = new PDO('mysql:host=localhost;port=3308;dbname=ngounou', 'root', '',[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
  ]);
 
?>