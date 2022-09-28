<?php
include 'config.php';
error_reporting(0);
session_start();

$id = $_GET['id'];
$op = $_GET['op'];

$sql = "SELECT * FROM _admin";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$npp_price = $row['newspaper_price'];
$cnr_price = $row['cnr_price'];

if($op == "npp" ){
    $sql = "UPDATE properties SET money = money - $npp_price WHERE id = $id";
    $result = mysqli_query($conn, $sql);
}


if($op == "cnr" ){
    $sql = "UPDATE properties SET money = money - $cnr_price WHERE id = $id";
    $result = mysqli_query($conn, $sql);
}

$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 0){
    $result = false;
}

if($result){
    echo 1;
}else{
    echo 0;
}
