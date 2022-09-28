<?php

include 'config.php';
error_reporting(0);
session_start();

if (isset($_POST['add'])) {

    $stock = $_POST['stock'];

    $sql = "INSERT INTO stocks (stock) VALUES ('$stock')";
    $result = mysqli_query($conn, $sql);

    $sql = "ALTER TABLE stocks_history ADD $stock FLOAT NOT NULL DEFAULT 0 AFTER ttime";
    $result = mysqli_query($conn, $sql);

    $sql = "ALTER TABLE properties ADD $stock INT(11) NOT NULL DEFAULT 0 AFTER money";
    $result = mysqli_query($conn, $sql);

}

if (isset($_POST['remove'])) {
    $stock = $_POST['stock'];

    $sql = "DELETE FROM stocks WHERE stock = '$stock' ";
    $result = mysqli_query($conn, $sql);

    $sql = "ALTER TABLE stocks_history DROP $stock";
    $result = mysqli_query($conn, $sql);

    $sql = "ALTER TABLE properties DROP $stock";
    $result = mysqli_query($conn, $sql);
}

header("location: ../admin.php#stocks");