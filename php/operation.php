<?php

include 'config.php';
error_reporting(0);
session_start();

date_default_timezone_set('Africa/Algiers');

if (isset($_POST['ph1_submit'])){

    $ttime = date("h:i:s") ;
    $trader = (int) $_POST['trader'];
    $SB = "B";
    $bank = (int) $_SESSION['id'];
    $stock = $_POST['stock'];
    $nbr = (int) $_POST['nbr'];
    $done = 0;

    

    $sql ="SELECT value, nbr  FROM stocks WHERE stock = '$stock'";

    echo $sql . "<br>";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $avaliable = $row['nbr'];
    $price =$row['value'];
    $fixed_price = $price;

    echo " 1 -- " . $avaliable . "--" . $price . "--" . $fixed_price . "--" . $nbr . "<br>" ;

    $sql = "SELECT money,$stock FROM properties WHERE id = $trader";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $money = $row['money'];
    $preasset = $row[$stock];
    echo $sql . "<br>";

    echo " 2 -- " . $money . "--" . $stocks_value . "--" . $preasset . "<br>";

    $gain = "tau".$_SESSION['day'];
    $sql = "SELECT op_done,trd_done,$gain FROM banks WHERE id = $bank";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $op_donne = $row['op_done'] ;
    $allowed_trades = (int) ($op_donne / 3);
    $trades_donne = $row['trd_done'];
    $tau = $row[$gain];

    echo $sql . "<br>";

    echo " 3 -- " . $trades_donne . "--" . $allowed_trades . "--" . $tau . "<br>";


    $money_left = $money - ($nbr * $fixed_price) * (1 + $tau * 0.01) ;

    if ((( $trader >= 3000 && $trader == $bank && $allowed_trades > $trades_donne) || $trader < 3000 ) && ($money_left > 0 && $avaliable >= $nbr ) && ($nbr != 0)){

        $nbrap = $preasset + $nbr;

        $sql = "UPDATE properties SET money = $money_left , $stock = $nbrap WHERE id = $trader";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";

        $avaliable -= $nbr;
        $sql = "UPDATE stocks SET nbr = $avaliable  WHERE stock = '$stock'";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";

        $win = (float) ($nbr * $fixed_price) * (0.01 * $tau);
        $sql = "UPDATE properties SET money = money + $win WHERE id = $bank";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";

        if ( $trader < 3000){
            $op_donne += 1;
        }else {
            $trades_donne += 1;   
        }

        $sql = "UPDATE banks SET op_done = $op_donne,trd_done = $trades_donne WHERE id = $bank";
        $result = mysqli_query($conn, $sql);
        $done = 1;
        echo $sql . "<br>";

        $sql = "INSERT INTO op_history (ttime, trader, SB, bank, nbr, stock, price, fixed_price, done) 
        VALUES ('$ttime', $trader, '$SB', $bank, $nbr, '$stock', $price, $fixed_price, $done)";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";
        $_SESSION['of'] = 0;

    } else {
        echo "operation failed";
        $_SESSION['of'] = 1;
    }
    header("location: ../banker.php");
}



if (isset($_POST['ph2_submit'])) {

    $ttime = date("h:i:s");
    $trader = (int) $_POST['trader'];
    $SB = $_POST['SB'];
    $bank = (int) $_SESSION['id'];
    $stock = $_POST['stock'];
    $nbr = (int) $_POST['nbr'];
    $price = $_POST['value'];
    $done = 0;



    $sql = "SELECT value  FROM stocks WHERE stock = '$stock'";

    echo $sql . "<br>";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $s_price = $row['value'];

    $pchecked = false;
    if( $price <= $s_price*(1+0.05) && $price >= $s_price * (1 - 0.05 )){
        $pchecked = true;
    }

    echo " 1 -- " . $price . "<br>";

    $sql = "SELECT money,$stock FROM properties WHERE id = $trader";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $money = $row['money'];
    $preasset = $row[$stock];
    echo $sql . "<br>";

    echo " 2 -- " . $money . "--" . $preasset . "<br>";

    $gain = "tau" . $_SESSION['day'];
    $sql = "SELECT op_done,trd_done,$gain FROM banks WHERE id = $bank";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $op_donne = $row['op_done'];
    $allowed_trades = (int) ($op_donne / 3);
    $trades_donne = $row['trd_done'];
    $tau = $row[$gain];

    echo $sql . "<br>";

    echo " 3 -- " . $trades_donne . "--" . $allowed_trades . "--" . $tau . "<br>";




    if ($SB == 'B') {
        $nbrap = $preasset ;
        $money_left = $money - ($nbr * $price) * (1 + $tau * 0.01);
    } else {
        $nbrap = $preasset - $nbr;
        $money_left = $money - ($nbr * $price) * ($tau * 0.01);
    }

    $bm = ($nbr * $price) * ($tau * 0.01);


    if ((($trader >= 3000 && $trader == $bank && $allowed_trades > $trades_donne) || $trader < 3000) && ($money_left > 0 && $pchecked && $nbrap >= 0 ) && ($nbr != 0)) {
        

        $sql = "UPDATE properties SET money = $money_left,$stock = $nbrap WHERE id = $trader";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";

        $sql = "UPDATE properties SET money = money + $bm WHERE id = $bank";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";


        if ($trader < 3000) {
            $op_donne += 1;
        } else {
            $trades_donne += 1;
        }

        $sql = "UPDATE banks SET op_done = $op_donne,trd_done = $trades_donne WHERE id = $bank";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";

        $sql = "INSERT INTO op_history (ttime, trader, SB, bank, nbr, stock, price, done) 
        VALUES ('$ttime', $trader, '$SB', $bank, $nbr, '$stock', $price, $done)";
        $result = mysqli_query($conn, $sql);
        echo $sql . "<br>";
        $_SESSION['of'] = 0;
    } else {
        echo "operation failed";
        $_SESSION['of'] = 1;
    }
    header("location: ../banker.php");
}


if ($_SESSION['phase'] > 2){
    require 'fixing.php';
    ####
    require 'fixing.php';
    ####
    require 'fixing.php';
    ####
    require 'fixing.php';
    ####
    require 'fixing.php';
}