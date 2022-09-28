<?php

include 'config.php';
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {

    $password = md5($_POST['password']);
    $id = $_SESSION['id'];

    $sql = "SELECT * FROM banks WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $pw = $row["_password"];

    if ($password == $pw) {

        
        $day = $_SESSION["day"];
        $gain = $_POST['gain'];
        $tau = "tau".$day;

        $sql = "SELECT $tau FROM banks WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            $sql = "ALTER TABLE banks ADD $tau FLOAT NOT NULL DEFAULT 0 ;";
            $result = mysqli_query($conn, $sql);
        }
        
        $sql="UPDATE banks SET $tau = $gain WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        

    } else {
        $disper == 1;
    }
    header("location: ../banker.php#tau");
}
