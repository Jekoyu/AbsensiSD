<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=nodemcu_absensi', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($pdo){
        echo "Success";
    }else{
        echo "Failed";
    }
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
