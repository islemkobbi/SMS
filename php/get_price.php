<?php

include 'config.php';
error_reporting(0);
session_start();

$stock = $_POST['stock'];
$sql = "SELECT value FROM stocks WHERE stock = '$stock'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$price = round($row['value'],3);

if ($_SESSION['phase'] == 1){
    echo "price : ". $price ."$" ;
    
} elseif ($_SESSION['phase'] >= 2){
    $min = $price - $price * 0.05;
    $max = $price + $price * 0.05;
    echo "price betwen : " . round($min,3) . "$ and " . round($max,3) . "$";
}
