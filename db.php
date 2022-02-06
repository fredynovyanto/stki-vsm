<?php 

    $dsn = "mysql:host=localhost;dbname=stki";

    try{
        $pdo = new PDO($dsn, 'root', '');
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
date_default_timezone_set('Asia/Jakarta');
?>