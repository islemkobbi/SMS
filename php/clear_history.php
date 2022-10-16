<?php

include 'config.php';


$sql = "SELECT * FROM _admin LIMIT 1";
$result1 = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result1);
$trader_cap = $row['trader_cap'];
$bank_cap = $row['bank_cap'];


$sql = "UPDATE properties SET money = CASE WHEN id < 3000 THEN $trader_cap ELSE $bank_cap END";
$result2 = mysqli_query($conn, $sql);

$sql = "SELECT stock FROM stocks";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    $stock = $row['stock'];
    $sql = "UPDATE properties SET $stock = 0";
    $result2 = mysqli_query($conn, $sql);

}


$sql = "UPDATE banks SET op_done = 0 ,trd_done = 0";
$result3 = mysqli_query($conn, $sql);


$sql = "DELETE FROM op_history;";
$result4 = mysqli_query($conn, $sql);


$sql = "DELETE FROM stocks_history;";
$result5 = mysqli_query($conn, $sql);


if($result1 && $result2 && $result3 && $result4 && $result5){
    echo 1;
}else  {
    echo 0;
}