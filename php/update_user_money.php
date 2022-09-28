<?php

include 'config.php';
error_reporting(0);
session_start();

if (isset($_POST['id'])) {
    sleep(1);
    $id = $_POST['id'];
    $value = $_POST['value'];

    $sql = "UPDATE properties SET money = $value WHERE id = $id ";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "done";
    } else {
        echo "error";
    }
}
