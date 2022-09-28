<?php
include 'config.php';
error_reporting(0);
session_start();

if (isset($_POST['phase'])) {
    
    $phase = $_POST["phase"];
    $sql = "UPDATE _admin SET phase = $phase LIMIT 1";
    $result = mysqli_query($conn, $sql);
    echo "set phase : " . $phase ;
}

if (isset($_POST['day'])) {
    $day = $_POST["day"];

    $sql = "UPDATE _admin SET day = $day LIMIT 1";
    $result = mysqli_query($conn, $sql);
    echo "set day :" . $day;
}

if (isset($_POST['ref_rate'])) {
    $ref_rate = $_POST["ref_rate"];
    $sql = "UPDATE _admin SET ref_rate = $ref_rate LIMIT 1";
    $result = mysqli_query($conn, $sql);
    echo "set ref :" . $ref_rate;
}

if (isset($_POST['trader_cap'])) {
    $trader_cap = $_POST["trader_cap"];
    $sql = "UPDATE _admin SET trader_cap = $trader_cap LIMIT 1";
    $result = mysqli_query($conn, $sql);
    echo "trader_cap :" . $trader_cap;
}


if (isset($_POST['bank_cap'])) {
    $bank_cap = $_POST["bank_cap"];
    $sql = "UPDATE _admin SET bank_cap = $bank_cap LIMIT 1";
    $result = mysqli_query($conn, $sql);
    echo "bank_cap :" . $bank_cap;
}

if (isset($_POST['cnr_price'])) {
    $cnr_price = $_POST["cnr_price"];
    $sql = "UPDATE _admin SET cnr_price = $cnr_price LIMIT 1";
    $result = mysqli_query($conn, $sql);
    echo "cnr_price :" . $cnr_price;
}

if (isset($_POST['newspaper_price'])) {
    $newspaper_price = $_POST["newspaper_price"];
    $sql = "UPDATE _admin SET newspaper_price = $newspaper_price LIMIT 1";
    $result = mysqli_query($conn, $sql);
    echo "newspaper_price :" . $newspaper_price;
}

