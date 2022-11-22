<?php
include 'config.php';

$sql = "SELECT breaking FROM _admin LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo $row['breaking'];

if(isset($_POST['breaking'])){
    $br = $_POST['breaking'];
    $sql = "UPDATE _admin SET breaking = $br";
    $result = mysqli_query($conn, $sql);
}