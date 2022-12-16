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

if (!isset($_SESSION['tabdisp'])) {
    $_SESSION['tabdisp'] = 0;
}


?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>charts</title>
</head>

<body>

    <div id="breaking">
        <div class="text">Breaking News</div>
        <div class="news"></div>
        <audio id="audio">
            <source src="audio/notif.mp3" type="audio/mpeg">
        </audio>
    </div>


    <?php if ($phase == 0) { ?>
        <div class="bgvideo">
            <video autoplay muted loop>
                <source src="assets/bgvideo.mp4" type="video/mp4">
            </video>
        </div>
    <?php } else { ?>
        <nav class="bottom-bar">
            <div style="font-size:xx-large;color:#fff;padding-left: 20px"> day <?= $day  ?> &HorizontalLine; <?php if ($phase == 4) {
                                                                                                                    echo "Ranking";
                                                                                                                } else {
                                                                                                                    echo "Phase " . $phase;
                                                                                                                } ?> </div>
            <div class="logos">
                <img src="assets/caplogo.png" alt="CAP_logo">
                <div class="separator"></div>
                <img src="assets/SMS-blanc.png" alt="SMS_logo">
            </div>
        </nav>

        <div class="disp_contaner">
            <section class="ph3 chartssec"></section>
            <section class="ph1"></section>
            <section class="ph2"></section>
            <section class="ph4"></section>
        </div>

    <?php } ?>



    <script src="package\jquery-latest.js"></script>
    <script>
        var phase = <?= $phase ?>;
        var b = 0;
        var a = 0;


        setInterval(function breaking() {
            $.ajax({
                url: 'php/breaking.php',
                success: function(php_result) {

                    console.log(php_result);
                    if (php_result == 1 && localStorage['news'] == 1) {
                        document.getElementById('breaking').style.display = "block";
                        document.getElementById('breaking').style.top = "0";
                        if (a == 0) {
                            document.getElementById("audio").play();
                            a = 1;
                        };
                        b = 1;
                    } else if (php_result == 1 && localStorage['news'] == 0) {
                        setTimeout(function() {
                            localStorage['news'] = 1;
                            location.reload(true);
                        }, 500);
                    } else {
                        b = 0;
                        localStorage['news'] = 0;
                        document.getElementById('breaking').style.top = "-110vh";
                        document.getElementById('breaking').style.display = "none";
                        a = 0;
                    }
                }
            })
        }, 1000);


        $(document).ready(function() {
            console.log(b);
            if (b == 0) {
                setInterval(function() {
                    $.ajax({
                        url: 'php/getphase.php',
                        success: function(php_result) {
                            console.log(php_result);
                            if (php_result != <?= $phase ?>) {
                                location.reload(true);
                            }
                        }
                    })
                }, 1000);
            };


            if (phase == 1) {
                $(".ph1").load("charts/ph1.php");
                setInterval(function() {
                    $(".ph1").load("charts/ph1.php");
                }, 10000);
            };

            if (phase == 2) {
                $(".ph2").load("charts/ph2.php");
                setInterval(function() {
                    $(".ph2").load("charts/ph2.php");
                }, 5000);
            };

            if (phase == 3) {
                $(".ph3").load("charts/ph3.php");
                setInterval(function() {
                    $(".ph3").load("charts/ph3.php");
                }, 10000);
            };
            if (phase == 4) {
                $(".ph4").load("charts/ph4.php");
                setInterval(function() {
                    $(".ph4").load("charts/ph4.php");
                }, 10000);
            };


        });
    </script>
</body>


</html>