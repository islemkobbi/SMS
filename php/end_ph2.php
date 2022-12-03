<?php


require 'fixing.php';
####
require 'fixing.php';
####
require 'fixing.php';
####
require 'fixing.php';
####
require 'fixing.php';
####
require 'fixing.php';
####
require 'fixing.php';
####
require 'fixing.php';
####
require 'fixing.php';


################################################################

$sql1 = "SELECT * FROM op_history WHERE done = 0";
$result1 = mysqli_query($conn, $sql1);


while ($row = $result1->fetch_assoc()) {

    echo " <br> -- 1 222222222222ssssssssssssssss<br>";

    $trader2 = $row['trader'];
    $op_nbr2 = $row['op_nbr'];
    $sb2 = $row['SB'];
    $nbr2 = $row['nbr'];
    $price2 = $row['price'];


    echo " <br> -- 2";


    $sql22 = "UPDATE op_history SET done = -1 WHERE op_nbr = $op_nbr2 ";
    $result22 = mysqli_query($conn, $sql22);

    if (
        $sb == 'B'
    ) {
        $m = $nbr * $price;
        $s = 0;
    } else {
        $m = 0;
        $s = $nbr;
    }

    $sql = "UPDATE properties SET $stock = $stock + $s, money = money + $m WHERE id = $trader ";
    $result = mysqli_query($conn, $sql);
}
####################################################################