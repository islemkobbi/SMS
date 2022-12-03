<?php

include 'config.php';
error_reporting(0);
session_start();

$sql = "SELECT phase FROM _admin";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo $row["phase"];