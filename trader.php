<?php

include 'php/config.php';
error_reporting(0);
session_start();

if (!isset($_SESSION["t"])) {
    $_SESSION["t"] = true;
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];

    $pw = '000';
    $_SESSION["t"] = true;

    if ($password == $pw) {
        $_SESSION["t"] = false;
    } else {
        echo "<script>alert('Password Not Matched.')</script>";
    }
}

$sql = "SELECT * FROM _admin LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$phase = $row['phase'];
$day = $row['day'];
$newspaper_price = $row['newspaper_price'];
$cnr_price = $row['cnr_price'];



$stocks = array();
$sql = "SELECT stock FROM stocks";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    array_push($stocks, $row['stock']);
}
$_SESSION['stocks'] = $stocks;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/fa/all.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>Trader</title>
</head>

<body>

    <?php

    if ($_SESSION["a"]) {

    ?>
        <!-- login ----------------------------------------------------------------------------- -->

        <div class="login">
            <section id="login">
                <div class="login-img">
                    <img src="assets/SMS-blanc.png" alt="SMS LOGO">
                </div>
                <form action="" method="POST">
                    <div class="input-group">
                        <label>password</label>
                        <input type="password" placeholder="xxxxxxxxxx" name="password" required>
                    </div>
                    <div class="input-group">
                        <button name="submit" class="btn">submit</button>
                    </div>
                </form>
            </section>
        </div>
        <!-- end login ----------------------------------------------------------------------------- -->
    <?php
    } else {
    ?>


        <!-- alerts -------------------------------------------->
        <div class="alert red-alert" id="red-alert">
            <div>
                <i>
                    <div class="fa-solid fa-triangle-exclamation"></div>
                </i>
                <p>Opreration Failed.</p>
            </div>
        </div>

        <div class="alert green-alert" id="green-alert">
            <div>
                <i>
                    <div class="fa-solid fa-circle-check"></div>
                </i>
                <p>Opreration succeed.</p>
            </div>
        </div>
        <script>
            function red_alert() {
                var element = document.getElementById("red-alert");
                setTimeout(function() {
                    element.style.right = "50px";
                }, 500);
                setTimeout(function() {
                    element.style.right = "-350px";
                }, 3000);
            };

            function green_alert() {
                var element = document.getElementById("green-alert");
                setTimeout(function() {
                    element.style.right = "50px";
                }, 500);
                setTimeout(function() {
                    element.style.right = "-350px";
                }, 3000);
            };
        </script>

        <!-- end alerts -------------------------------------------->

        <!-- sidebar ----------------------------------------------------------------------------- -->
        <div class="sidebar">
            <div class="top-logo">
                <img class="logo1" src="assets/SMS-blanc.png" alt="SMS logo">
            </div>
            <ul class="nav-list">
                <li><a href="#" class="name">
                        <i>
                            <div class="fa-solid fa-user-tie"></div>
                        </i>
                        <p>Trader</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#Propreties">
                        <i>
                            <div class="fa-solid fa-wallet"></div>
                        </i>
                        <p>Propreties</p>
                    </a>
                </li>
                <li class="nav-item"><a href="php/logout_trader.php">
                        <i>
                            <div class="fa-solid fa-sign-out"></div>
                        </i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>

            <div class="bottom-logo">
                <div class="separator"></div>
                <img class="logo2" src="assets/caplogo.png" alt="CAP logo">
            </div>
        </div>
        <!-- end sidebar ----------------------------------------------------------------------------- -->
        <!-- content ----------------------------------------------------------------------------- -->
        <div class="content">
            <!-- Propreties ------------------------------------------------------------->
            <div id="Propreties" class="section">
                <section>
                    <div class="input-group">
                        <label>ID </label>
                        <input id="uid" type="text" placeholder="1023">
                    </div>
                    <div class="input-group">
                        <button class="btn" onclick="show_prop()">Show</button>
                        <button class="btn btn-red" onclick="hide_prop()" style="margin-top: 5px;">H5de</button>
                        <button class="btn btn-orange" onclick="sell_npp()" style="margin-top: 5px;">Sell Newspaper</button>
                    </div>

                    <div id="uprop"></div>
                </section>
            </div>
            <script src="package/jquery-latest.js"></script>
            <script>
                function show_prop() {
                    var uid = document.getElementById('uid').value;
                    $.ajax({
                        url: 'php/sell_trader.php',
                        type: 'GET',
                        data: {
                            op: "cnr",
                            id: uid
                        },
                        success: function(php_result) {
                            if (php_result == 1) {
                                green_alert();
                            } else {
                                red_alert();
                            }
                            uid = encodeURIComponent(uid);
                            $("#uprop").load('php/Propreties_trader.php?uid=' + uid);
                        }
                    })
                }

                function hide_prop() {
                    $("#uprop").empty();
                }


                function sell_npp() {
                    var uid = document.getElementById('uid').value;
                    $.ajax({
                        url: 'php/sell_trader.php',
                        type: 'GET',
                        data: {
                            op: "npp",
                            id: uid
                        },
                        success: function(php_result) {
                            if (php_result == 1) {
                                green_alert();
                            } else {
                                red_alert();
                            }
                        }
                    })
                }
            </script>

            <!-- Propreties ------------------------------------------------------------->

        <?php } ?>

        </div>
        <!-- end content ----------------------------------------------------------------------------- -->
        <script src="js/fa/all.js"></script>
</body>


</html>