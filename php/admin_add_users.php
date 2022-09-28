<?php

include 'config.php';
error_reporting(0);
session_start();

$sql = "SELECT * FROM _admin LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$trader_cap = $row['trader_cap'];
$bank_cap = $row['bank_cap'];


if (isset($_POST['usubmit'])) {
    $name = $_POST['name'];
    $id = (int) $_POST['id'];
    $pw = md5($_POST['password2']);

    $sql = "INSERT INTO users (id, fullname ) VALUES ($id, '$name' )";
    $result = mysqli_query($conn, $sql);

    $money = $trader_cap;
    if ($id > 3000) {

        $sql = "INSERT INTO banks (id, _password) VALUES ($id, '$pw')";
        $result = mysqli_query($conn, $sql);

        $money = $bank_cap;
    }


    $sql = "INSERT INTO properties (id,money) VALUES ( $id ,$money)";
    $result = mysqli_query($conn, $sql);
    
}


if (isset($_POST['rsubmit'])) {

    $id = (int) $_POST['id'];

    $sql = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM properties WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if($id >= 3000){
        $sql = "DELETE FROM banks WHERE id = $id";
        $result = mysqli_query($conn, $sql);
    }
}
if ($id < 3000){
    header("location: ../admin.php#Traders");
} else {
    header("location: ../admin.php#Banks");
}