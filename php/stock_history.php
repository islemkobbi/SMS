<?php

include 'config.php';
error_reporting(0);
session_start();


$stocks = $_SESSION['stocks'];

$prices = array();
foreach ($stocks as $stock) {
    $sql = "SELECT value,rate FROM stocks WHERE stock = '$stock'";
    $result = mysqli_query($conn, $sql);
    $stock_row = mysqli_fetch_assoc($result);
    $price = $stock_row['value'] * (1+ $stock_row['rate']*0.01);
    array_push($prices, $stock_row['value']);

    $sql = "UPDATE stocks SET value = $price WHERE stock = '$stock'";
    $result = mysqli_query($conn, $sql);
}

$_SESSION['count'] += 1;

date_default_timezone_set("Africa/Algiers");
$sql = "INSERT INTO stocks_history ( ttime," . implode(', ', $stocks) . ") VALUES ( '" . date('h:i:s') . "'," .implode(', ', $prices) . ")"  ;
$result = mysqli_query($conn, $sql);

?>
<?= $_SESSION['count'] ?>