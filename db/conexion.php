<?php
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "db_crud_escola_0616_a07";

    try{  //QUITAR PORT 3307
        $conn = new PDO("mysql:host=$servername;dbname=$dbname",$dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $e){
        echo "Error en la conexión con el servidor de datos: ". $e->getMessage();
        die();  
    }


?>