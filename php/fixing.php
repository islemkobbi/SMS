<?php

include 'config.php';
error_reporting(0);
session_start();



$sql1 = "SELECT DISTINCT stock FROM op_history WHERE done = 0";
$result1 = mysqli_query($conn, $sql1);
echo $sql1;

while ($row1 = $result1->fetch_assoc()) {

    $stock = $row1['stock'];
    echo $stock . "<br>";

    $sql2 = "SELECT DISTINCT price FROM op_history WHERE done = 0 AND stock = '$stock' ORDER BY op_nbr ASC";
    $result2 = mysqli_query($conn, $sql2);
    $prices = array();
    
    while ($row2 = $result2->fetch_assoc()) {
        array_push($prices, $row2['price']);
    }

    

    $na = [];

    foreach ($prices as $price) {

        $p = $price * (0.99999);
        $sql3 = "SELECT SUM(nbr) AS nbr FROM op_history WHERE done = 0 AND stock= '$stock' AND SB = 'B' AND price >= $p";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);

        if (is_null($row3['nbr'])) {
            $nb = 0;
        } else {
            $nb = $row3['nbr'];
        }


        $p = $price * (1.00001);
        $sql4 = "SELECT SUM(nbr) AS nbr FROM op_history WHERE done = 0 AND stock = '$stock' AND SB = 'S' AND price <= $p";
        $result4 = mysqli_query($conn, $sql4);
        $row4 = mysqli_fetch_assoc($result4);

        if (is_null($row4['nbr'])) {
            $ns = 0;
        } else {
            $ns = $row4['nbr'];
        }

        array_push($na, min($ns, $nb));
        echo $price . "--" . "ns =" . $ns . " nb = " . $nb . " min = " . min($ns, $nb) . "<br>";

        #echo $sql3 ."<br>". $sql4."<br>";
    
    }
    print_r($prices);
    echo "<br> --------------------------------------------------------------";
    print_r($na);
    echo "<br>";

    $max = max($na);
    echo $max . "-------- <br>";
    
    if ($max > 0) {

        #$max_index = array_search($max, $na);
        #$fixed_price = $prices[$max_index];

        $i = 0;
        $max_p = [];
        foreach($na as $n){
            if($max == $n){
                array_push($max_p,$prices[$i]);
            }
            $i = $i + 1 ;
        }

        $fixed_price = max($max_p);


        echo "---- <br>" . $fixed_price . " <br> ----- <br>";

        $sql4 = "UPDATE op_history SET fixed_price = $fixed_price WHERE done = 0 AND stock = '$stock' ";
        $result4 = mysqli_query($conn, $sql4);

        $sql4 = "UPDATE stocks SET value = $fixed_price WHERE stock = '$stock' ";
        $result4 = mysqli_query($conn, $sql4);
    }
}

echo " <br> ------------------------------------------ <br>";


$sql = "SELECT * FROM op_history WHERE done = 0 AND (( fixed_price < price  AND SB = 'S') OR ( fixed_price > price AND SB = 'B'))";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {

    $trader = $row['trader'];
    $op_nbr = $row['op_nbr'];
    $sb = $row['SB'];
    $stock = $row['stock'];
    $nbr = $row['nbr'];
    $price = $row['price'];
    $fixed_price = $row['fixed_price'];

    $sql = "UPDATE op_history SET done = -1 WHERE op_nbr = $op_nbr ";
    $result = mysqli_query($conn, $sql);

    if ($sb == 'B') {
        $m = $nbr * $price;
        $s = 0;
    } else {
        $m = 0;
        $s = $nbr;
    }



    $sql = "UPDATE properties SET $stock = $stock + $s, money = money + $m WHERE id = $trader ";
    $result = mysqli_query($conn, $sql);
}





$list = array();

$sql = "SELECT * FROM op_history WHERE done = 0 AND fixed_price IS NOT NULL ORDER BY ttime ASC LIMIT 1";
$result = mysqli_query($conn, $sql);
$rownbr = mysqli_num_rows($result);

while ($rownbr != 0) {

    $row = mysqli_fetch_assoc($result);

    $trader = $row['trader'];
    $op_nbr = $row['op_nbr'];
    $sb = $row['SB'];
    $stock = $row['stock'];
    $nbr = $row['nbr'];
    $price = $row['price'];
    $fixed_price = $row['fixed_price'];

    array_push($list, $op_nbr);

    if ($sb == 'B') {
        $sbw = 'S';
    } else {
        $sbw = 'B';
    }

    $sql1 = "SELECT * FROM op_history WHERE done = 0 AND stock = '$stock' AND SB = '$sbw' AND fixed_price IS NOT NULL ORDER BY ttime ASC LIMIT 1";
    $result1 = mysqli_query($conn, $sql1);
    $rownbr2 = mysqli_num_rows($result1);

    echo $sql1 . "----" . $rownbr2 . " <br>";

    if ($rownbr2 > 0) {

        echo " <br> -- 1 222222222222<br>";
        $row = mysqli_fetch_assoc($result1);

        $trader2 = $row['trader'];
        $op_nbr2 = $row['op_nbr'];
        $sb2 = $row['SB'];
        $nbr2 = $row['nbr'];
        $price2 = $row['price'];

        $nbrdonne = min($nbr, $nbr2);

        if ($nbr - $nbrdonne > 0) {
            $done1 = 0;
        } else {
            $done1 = 1;
        }

        if ($nbr2 - $nbrdonne > 0) {
            $done2 = 0;
        } else {
            $done2 = 1;
        }

        if ($sb == 'B') {
            $m = $nbrdonne * ($price - $fixed_price);
            $s = $nbrdonne;
        } else {
            $m = $fixed_price * $nbrdonne;
            $s = 0;
        }

        if ($sb2 == 'B') {
            $m2 = $nbrdonne * ($price2 - $fixed_price);
            $s2 = $nbrdonne;
        } else {
            $m2 = $fixed_price * $nbrdonne;
            $s2 = 0;
        }

        $sql = "UPDATE properties SET $stock = $stock + $s, money = money + $m WHERE id = $trader ";
        $result = mysqli_query($conn, $sql);

        $sql = "UPDATE properties SET $stock = $stock + $s2, money = money + $m2 WHERE id = $trader2 ";
        $result = mysqli_query($conn, $sql);

        if ($donne1 == 1) {
            $sql = "UPDATE op_history SET done = $done1 WHERE op_nbr = $op_nbr ";
            $result = mysqli_query($conn, $sql);
        } else {
            $sql = "UPDATE op_history SET done = $done1, nbr = nbr - $nbrdonne WHERE op_nbr = $op_nbr ";
            $result = mysqli_query($conn, $sql);
        }


        if ($donne2 == 1) {
            $sql = "UPDATE op_history SET done = $done2 WHERE op_nbr = $op_nbr2 ";
            $result = mysqli_query($conn, $sql);
        } else {
            $sql = "UPDATE op_history SET done = $done2, nbr = nbr - $nbrdonne WHERE op_nbr = $op_nbr2 ";
            $result = mysqli_query($conn, $sql);
        }
    }/* else {

        echo " <br> -- 2";


        $sql = "UPDATE op_history SET done = -1 WHERE op_nbr = $op_nbr ";
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


    }*/

    $sql = "SELECT * FROM op_history WHERE done = 0 and op_nbr NOT IN (" . implode(", ", $list) . ") ORDER BY ttime ASC LIMIT 1";
    echo $sql . "<br>";
    $result = mysqli_query($conn, $sql);
    $rownbr = mysqli_num_rows($result);
    
}

echo " <br> ------------------------------------------ <br>";

$sql = "SELECT * FROM op_history WHERE done = 0";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {

    $trader = $row['trader'];
    $op_nbr = $row['op_nbr'];
    $sb = $row['SB'];
    $stock = $row['stock'];
    $nbr = $row['nbr'];
    $price = $row['price'];

    $sql = "SELECT value  FROM stocks WHERE stock = '$stock'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $s_price = $row['value'];

    if ($s_price * 1.05 < $price || $s_price * 0.95 > $price) {

        $sql = "UPDATE op_history SET done = -1 WHERE op_nbr = $op_nbr";
        $result = mysqli_query($conn, $sql);

        if ($sb == 'B') {
            $m = $nbr * $price;
            $s = 0;
        } else {
            $m = 0;
            $s = $nbr;
        }

        $sql = "UPDATE properties SET $stock = $stock + $s, money = money + $m WHERE id = $trader";
        $result = mysqli_query($conn, $sql);
    }
}
