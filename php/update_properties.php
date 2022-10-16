<?php

include 'config.php';
error_reporting(0);
session_start();

if (isset($_POST['id'])) {
    $para = $_POST['para'];
    $value = $_POST['value'];
    $id = $_POST['id'];

    $sql = "UPDATE properties SET $para = $value WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "done";
    } else {
        echo "error";
    }
}
