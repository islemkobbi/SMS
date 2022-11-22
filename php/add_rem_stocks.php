<?php

include 'config.php';
error_reporting(0);
session_start();

if (isset($_POST['add'])) {

    $stock = strtoupper( $_POST['stock'] );

    $sql = "SELECT value FROM stocks WHERE stock = '$stock'";
    $result = mysqli_query($conn, $sql);
    $nbr = mysqli_num_rows($result);

    if ($nbr == 0){

        $sql = "INSERT INTO stocks (stock) VALUES ('$stock')";
        $result = mysqli_query($conn, $sql);

        $sql = "ALTER TABLE stocks_history ADD $stock FLOAT NOT NULL DEFAULT 0 AFTER ttime";
        $result = mysqli_query($conn, $sql);

        $sql = "ALTER TABLE properties ADD $stock INT(11) NOT NULL DEFAULT 0 AFTER money";
        $result = mysqli_query($conn, $sql);
    }

}

if (isset($_POST['remove'])) {
    $stock = strtoupper( $_POST['stock'] );

    $sql = "SELECT value FROM stocks WHERE stock = '$stock'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $price = $row["value"];

    $sql2 = "SELECT id,$stock FROM properties";
    $result2 = mysqli_query($conn, $sql2);

    while ($row2 = $result2->fetch_assoc()) {
        $win = $row2[$stock] * $price;
        $id = $row2['id'];
        $sql3 = "UPDATE properties SET money = money + $win WHERE id = $id ";
        $result3 = mysqli_query($conn, $sql3);
    }

    $sql = "DELETE FROM stocks WHERE stock = '$stock' ";
    $result = mysqli_query($conn, $sql);

    $sql = "ALTER TABLE stocks_history DROP $stock";
    $result = mysqli_query($conn, $sql);

    $sql = "ALTER TABLE properties DROP $stock";
    $result = mysqli_query($conn, $sql);
}

header("location: ../admin.php#stocks");