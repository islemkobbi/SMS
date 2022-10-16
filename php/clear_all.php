<?php

include 'config.php';

$sql = "SELECT stock FROM stocks";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    $stock = $row['stock'];
    $sql = "ALTER TABLE properties DROP $stock";
    $resultd = mysqli_query($conn, $sql);

    $sql = "ALTER TABLE stocks_history DROP $stock";
    $resultd = mysqli_query($conn, $sql);
}



$sql = "DELETE FROM users;";
$result1 = mysqli_query($conn, $sql);
$sql = "DELETE FROM properties;";
$result2 = mysqli_query($conn, $sql);
$sql = "DELETE FROM banks;";
$result3 = mysqli_query($conn, $sql);
$sql = "DELETE FROM op_history;";
$result4 = mysqli_query($conn, $sql);
$sql = "DELETE FROM stocks_history;";
$result5 = mysqli_query($conn, $sql);
$sql = "DELETE FROM stocks";
$result6 = mysqli_query($conn, $sql);

if($result1 && $result2 && $result3 && $result4 && $result5 && $result6){
    echo 1;
}else  {
    echo 0;
}