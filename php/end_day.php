<?php

include 'fixing.php';
include 'fixing.php';
include 'fixing.php';

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


        $sql = "UPDATE op_history SET done = -1 WHERE op_nbr = $op_nbr2 ";
        $result = mysqli_query($conn, $sql);

        if ($sb == 'B'
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




$sql= "SELECT stock,benefits FROM stocks";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()){

    $stock = $row['stock'];
    $benefits = $row['benefits'];

    $sql2 = "SELECT id,$stock FROM properties";
    $result2 = mysqli_query($conn, $sql2);
    
    while ($row2 = $result2->fetch_assoc()) {
        $win = $row2[$stock] * $benefits;
        $id = $row2['id']; 
        $sql3 = "UPDATE properties SET money = money + $win WHERE id = $id ";
        $result3 = mysqli_query($conn, $sql3);

    }


}
