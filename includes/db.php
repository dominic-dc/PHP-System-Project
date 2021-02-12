<?php

    $dsn = "mysql:host=localhost;dbname=dominic";

    try {
        $pdo = new PDO($dsn, 'root', '', array(PDO::ATTR_PERSISTENT => true));
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
?>