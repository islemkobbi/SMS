<?php

include 'config.php';
error_reporting(0);
session_start();

if (isset($_POST['stock'])) {
$stock = $_POST['stock'];
$value = $_POST['value'];
$para = $_POST['para'];

$sql = "UPDATE stocks SET $para = $value WHERE stock = '$stock'";
$result = mysqli_query($conn, $sql);

if($result) {
    echo "done";
} else {
    echo "error";
}

}

