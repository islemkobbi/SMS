<!DOCTYPE html>
<html lang="en">
<?php

include 'php/config.php';
error_reporting(0);
session_start();

$stocks = array();
$sql = "SELECT stock FROM stocks";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    array_push($stocks, $row['stock']);
}
$_SESSION['stocks'] = $stocks;

$sql = "SELECT * FROM _admin LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$phase = $row['phase'];
$day = $row['day'];

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>charts</title>
</head>

<body>

    <script src="package\jquery-latest.js"></script>
    <script>
        var phase = <?= $phase ?>;

        $(document).ready(function() {

            setInterval(function() {
                location.reload();
            }, 60000)

            if (phase == 1) {
                $(".ph1").load("charts/ph1.php");
                setInterval(function() {
                    $(".ph1").load("charts/ph1.php");
                }, 1000);
            };

            if (phase == 2) {
                $(".ph2").load("charts/ph2.php");
                setInterval(function() {
                    $(".ph2").load("charts/ph2.php");
                }, 1000);
            };

            if (phase == 3) {
                $(".ph3").load("charts/ph3.php");
                setInterval(function() {
                    $(".ph3").load("charts/ph3.php");
                }, 10000);
            };
            if (phase == 4) {
                $(".ph4").load("charts/ph4.php");
            };


        });
    </script>

    <nav class="bottom-bar">
        <div style="font-size:xx-large;color:#fff;padding-left: 20px"> day <?= $day  ?> &HorizontalLine; <?php if ($phase == 4 ){echo "Ranking"; }else { echo "Phase " . $phase; }?> </div>
        <div class="logos">
            <img src="assets/caplogo.png" alt="cap_logo">
            <div class="separator"></div>
            <img src="assets/SMS-blanc.png" alt="sms logo">
        </div>
    </nav>

    <div class="disp_contaner">
        <section class="ph3 chartssec"></section>
        <section class="ph1"></section>
        <section class="ph2"></section>
        <section class="ph4"></section>
    </div>
</body>


</html>