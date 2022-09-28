<?php

include 'php/config.php';
error_reporting(0);
session_start();

if (!isset($_SESSION["b"])) {
    $_SESSION["b"] = true;
}

$sql = "SELECT * FROM _admin";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$_SESSION["phase"] = (int) $row["phase"];
$_SESSION["day"] = (int) $row["day"];



if (isset($_POST['password'])) {

    $password = md5($_POST['password']);
    $_SESSION['id'] = $_POST['id'];
    $id = $_SESSION['id'];

    $sql = "SELECT * FROM banks WHERE id = $id ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $pw = $row["_password"];

    if ($password == $pw) {
        $_SESSION["b"] = false;
    } else {
        $_SESSION['disper'] = 1;
    }
    header("location: banker.php");
}


if ($_SESSION['b'] == false) {

    $id = $_SESSION['id'];

    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $_SESSION['bname'] = $row["fullname"];

    $_SESSION['disper'] = 0;
}

$sql = "SELECT * FROM banks WHERE id =$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$taustr = "tau" . $_SESSION["day"];
$tau = $row[$taustr];
$trades_all = (int) ($row['op_done'] / 3 - $row['trd_done']);

if (isset($_SESSION['disper']) and $_SESSION['disper'] == 1) {
    echo "<script>alert('Password Not Matched.')</script>";
    $disper = 0;
}

if (isset($_SESSION['of']) and $_SESSION['of'] == 1) {
    echo "<script> window.addEventListener('load', function() {
            red_alert();
        }) </script>";
    unset($_SESSION['of']);
}

if (isset($_SESSION['of']) and $_SESSION['of'] == 0) {
    echo "<script> window.addEventListener('load', function() {
            green_alert();
        }) </script>";
    unset($_SESSION['of']);
}


$stocks = array();
$sql = "SELECT stock FROM stocks";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    array_push($stocks, $row['stock']);
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/fa/all.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">


    <title>Banker</title>
</head>

<body>

    <script src="package\jquery.min.js"></script>
    <script>
        function getprice(element) {
            var stock = element.value

            $.ajax({
                url: 'php/get_price.php',
                type: 'POST',
                data: {
                    stock: stock
                },
                success: function(php_result) {
                    console.log(php_result);
                    $("#price").attr("placeholder", php_result);
                }
            })
        }
    </script>

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
            console.log('enterd')
        };

        function green_alert() {
            var element = document.getElementById("green-alert");
            setTimeout(function() {
                element.style.right = "50px";
            }, 500);
            setTimeout(function() {
                element.style.right = "-350px";
            }, 3000);
            console.log('enterd')
        };
    </script>

    <!-- end alerts -------------------------------------------->


    <?php if ($_SESSION['b']) { ?>
        <div class="login">
            <section id="login">
                <div class="login-img">
                    <img src="assets/SMS-blanc.png" alt="SMS LOGO">
                </div>
                <form action="" method="POST" id="loginbank">
                    <div class="input-group">
                        <label>Bank ID</label>
                        <input type="text" placeholder="ex: 3024" name="id" required>
                    </div>
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
    <?php } else {
    ?> <div class="sidebar">
            <div class="top-logo">
                <img class="logo1" src="assets/SMS-blanc.png" alt="SMS logo">
            </div>
            <ul class="nav-list">
                <li><a href="#" class="name">
                        <i>
                            <div class="fa-solid fa-building-columns"></div>
                        </i>
                        <p><?= $_SESSION['bname'] ?></p>
                    </a>
                </li>
                <?php if ($_SESSION['phase'] == 0 and $_SESSION['day'] > 0) { ?>
                    <li class="nav-item"><a href="#tau">
                            <i>
                                <div class="fa-solid fa-percent"></div>
                            </i>
                            <p>Intrest rate</p>
                        </a>
                    </li>
                <?php } elseif ($_SESSION['phase'] > 0 and $_SESSION['phase'] < 4) { ?>
                    <li class="nav-item"><a href="#Transfers">
                            <i>
                                <div class="fa-solid fa-money-bill-transfer"></div>
                            </i>
                            <p>Transfers</p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item"><a href="#op_his">
                        <i>
                            <div class="fa-solid fa-clock-rotate-left"></div>
                        </i>
                        <p>Operations history</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#stocks">
                        <i>
                            <div class="fa-solid fa-money-bill-trend-up"></div>
                        </i>
                        <p>stocks</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#bank_prop">
                        <i>
                            <div class="fa-solid fa-wallet"></div>
                        </i>
                        <p>Bank properties</p>
                    </a>
                </li>
                <li class="nav-item"><a href="php/logout_bank.php">
                        <i>
                            <div class="fa-solid fa-sign-out"></div>
                        </i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>






            <div class="bottom-logo">
                <ul class="nav-list">
                    <li class="selected">
                        <p>Day : <?= $_SESSION['day'] ?></p>
                    </li>
                    <li class="<?php if ($_SESSION['phase'] == 1) {
                                    echo "selected";
                                } ?>">
                        <?php if ($_SESSION['phase'] == 1) { ?>
                            <i>
                                <div class="fa-solid fa-arrow-right"></div>
                            </i>
                        <?php } ?>
                        <p>phase 1 : Primery market</p>
                    </li>
                    <li class="<?php if ($_SESSION['phase'] == 2) {
                                    echo "selected";
                                } ?>">
                        <p>phase 2 : Secondary market</p>
                        <?php if ($_SESSION['phase'] == 2) { ?>
                            <i>
                                <div class="fa-solid fa-arrow-left"></div>
                            </i>
                        <?php } ?>
                    </li>
                    <li class="<?php if ($_SESSION['phase'] == 3) {
                                    echo "selected";
                                } ?>">
                        <p>phase 3 : Continuous market</p>
                        <?php if ($_SESSION['phase'] == 3) { ?>
                            <i>
                                <div class="fa-solid fa-arrow-left"></div>
                            </i>
                        <?php } ?>
                    </li>

                    <div class="separator"></div>
                    <img class="logo2" src="assets/caplogo.png" alt="CAP logo">
            </div>
        </div>
        <div class="content">
            <?php
            if ($_SESSION['phase'] == 0 and $_SESSION['day'] > 0) { ?>

                <div class="section" id="tau">
                    <section>
                        <form action="php/set_gain.php" method="POST">
                            <div class="input-group">
                                <label>intrest rate for day <?= $_SESSION['day'] ?></label>
                                <input type="text" placeholder="curent value: <?= $tau ?>" name="gain" required>
                            </div>
                            <div class="input-group">
                                <label>password</label>
                                <input type="password" placeholder="xxxxxxxx" name="password" required>
                            </div>
                            <div class="input-group">
                            </div>
                            <div class="input-group">
                                <button name="submit" class="btn">submit</button>
                            </div>
                        </form>
                    </section>
                </div>


            <?php } ?>

            <div class="section" id="Transfers">

                <?php if ($_SESSION['phase'] == 1) { ?>
                    <section>
                        <!-- ------------------------------- -->
                        <form action="php/operation.php" method="POST">
                            <div class="input-group">
                                <label>Trader ID</label>
                                <input type="text" placeholder="ex: 1023" name="trader" required>
                            </div>
                            <div class="input-group">
                                <label>stock</label>
                                <select name="stock" placeholder="select stock" onchange="getprice(this)">
                                    <option selected>--</option>
                                    <?php foreach ($stocks as $stock) { ?>
                                        <option value="<?= $stock ?>"><?= $stock ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>quantity</label>
                                <input type="text" placeholder="ex: 20" name="nbr" required>
                            </div>
                            <div class="input-group">
                                <button name="ph1_submit" class="btn">submit</button>
                            </div>
                        </form>
                    </section>
                <?php }
                if ($_SESSION['phase'] > 1 and $_SESSION['phase'] < 4) { ?>
                    <section>
                        <form action="php/operation.php" method="POST">
                            <div class="input-group">
                                <label>Trader ID</label>
                                <input type="text" placeholder="ex: 1023" name="trader" required>
                            </div>
                            <div class="input-group">
                                <label>Seel or Buy</label>
                                <select name="SB">
                                    <option selected>--</option>
                                    <option value="S">SELL</option>
                                    <option value="B">BUY</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Stock</label>
                                <select name="stock" onchange="getprice(this)">
                                    <option selected>--</option>
                                    <?php foreach ($stocks as $stock) { ?>
                                        <option value="<?= $stock ?>"><?= $stock ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Quantity</label>
                                <input type="text" placeholder="ex: 1023" name="nbr" required>
                            </div>
                            <div class="input-group">
                                <label>Price</label>
                                <input id="price" type="text" placeholder="--" name="value" required>
                            </div>
                            <div class="input-group">
                                <button name="ph2_submit" class="btn">Submit</button>
                            </div>
                        </form>
                    </section>
                <?php }  ?>

            </div>
            <div class="section" id="op_his">
                <section id="statbank">
                    <div class="statw">

                        <table>

                            <tr class=" stat0">
                                <td>Time</td>
                                <td>Trader ID</td>
                                <td>Stock</td>
                                <td>S/B</td>
                                <td>Number</td>
                                <td>Price</td>
                                <td>State</td>
                            </tr>

                            <tr style="background-color: transparent;">
                                <td colspan="7"></td>

                            </tr>

                            <?php
                            $sql1 = "SELECT * FROM op_history WHERE bank = $id ORDER BY op_nbr DESC LIMIT 20";
                            $result1 = mysqli_query($conn, $sql1);
                            while ($row1 = $result1->fetch_assoc()) {

                                if ($row1['done'] == 0) {
                                    $state = "assets/icons/o.png";
                                    $alt = "waiting";
                                } elseif ($row1['done'] == -1) {
                                    $state = "assets/icons/x.png";
                                    $alt = "failde";
                                } else {
                                    $state = "assets/icons/done.png";
                                    $alt = "done";
                                }
                                if ($row1['SB'] == "S") {
                                    $sb = "SELL";
                                } else {
                                    $sb = "BUY";
                                }

                            ?>


                                <tr class=" stat1">
                                    <td><?= $row1['ttime'] ?></td>
                                    <td><?= $row1['trader'] ?></td>
                                    <td><?= $row1['stock'] ?></td>
                                    <td><?= $sb ?></td>
                                    <td><?= $row1['nbr'] ?></td>
                                    <td><?= $row1['price'] ?></td>
                                    <td><img class="staticons" src="<?= $state ?>" alt="<?= $alt ?>"></td>
                                </tr>

                            <?php } ?>
                        </table>
                    </div>
                </section>
            </div>

            <div class="section" id="stocks">
                <section>
                    <div class="statw">
                        <table>
                            <th class="stat0">Stock</th>
                            <th class="stat0">benefits</th>
                            <th class="stat0">Number</th>
                            <th class="stat0">Price</th>
                            <th class="stat0" style="width: 20px; background-color: transparent;"></th>
                            <th class="stat0">Stock</th>
                            <th class="stat0">benefits</th>
                            <th class="stat0">Number</th>
                            <th class="stat0">Price</th>

                            <?php

                            $sql = "SELECT * FROM stocks";
                            $result = mysqli_query($conn, $sql);

                            while ($row = $result->fetch_assoc()) {
                                $stock = $row['stock'];
                                $nbr = $row['nbr'];
                                $val = $row['value'];
                                $benefits = $row['benefits'];


                            ?>
                                <tr class="stat1">
                                    <td><?= $stock ?></td>
                                    <td><?= $benefits ?> $</td>
                                    <td><?= $nbr ?></td>
                                    <td><?= $val ?> $</td>
                                    <td style=" background-color:#d3d3d3; padding:0"></td>

                                    <?php
                                    $row = $result->fetch_assoc();
                                    $stock = $row['stock'];
                                    $nbr = $row['nbr'];
                                    $val = $row['value'];
                                    $benefits = $row['benefits'];
                                    ?>


                                    <td><?= $stock ?></td>
                                    <td><?= $benefits ?> $</td>
                                    <td><?= $nbr ?></td>
                                    <td><?= $val ?> $</td>
                                </tr>

                            <?php } ?>
                        </table>
                    </div>
                </section>
            </div>

            <div class="section" id="bank_prop">
                <section>
                    <div class="statw">
                        <?php
                        $sql = "SELECT money FROM properties WHERE id = $id";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        ?>
                        <table>
                            <tr style="background-color: trarsparent; color:black;">
                                <td style=" text-align: left;font-size: large; color: #000;">
                                    Bank Name : <b> <?= $_SESSION['bname'] ?></b>
                                </td>
                                <td style="font-size: large; color: #000;">
                                    Bank ID : <b> <?= $id ?></b>
                                </td>
                                <td style="font-size: large; color: #000;">
                                    Allowed trades : <b> <?= $trades_all ?></b>
                                </td>
                                <td style="font-size: large; text-align: right; color: #000;">
                                    Money : <b> <?= $row['money'] ?> </b> $
                                </td>
                            </tr>
                        </table>
                        <br><br>

                        <table>
                            <?php
                            $i = 0;
                            $t = 0;
                            $sql1 = "SELECT * FROM properties WHERE id = $id LIMIT 1";
                            $result1 = mysqli_query($conn, $sql1);
                            $row1 = mysqli_fetch_assoc($result1);
                            $spr = min(count($stocks), 10);
                            while ($i < count($stocks)) { ?>

                                <tr class=" stat0">
                                    <?php for ($j = $i; $j < ($t + 1) * $spr; $j++) { ?>
                                        <td><?= $stocks[$j] ?></td>
                                    <?php } ?>
                                </tr>

                                <tr class="stat1">
                                    <?php for ($j = $i; $j < ($t + 1) * $spr; $j++) { ?>
                                        <td><?= $row1["$stocks[$j]"] ?></td>
                                    <?php } ?>
                                </tr>

                            <?php $i += $spr;
                                $t += 1;
                            } ?>
                        </table>

                    
                            <table style="margin-top: 20px;">
                                <tr class=" stat0" >
                                    <?php for ($i = 1; $i <= max($day, 4); $i++) {
                                        echo "<td> intrest rate day " . $i . "</td>";
                                    } ?>
                                </tr>

                                <?php
                                $sql = "SELECT * FROM banks WHERE id = $id";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);
                                ?>

                                    <tr class="stat1" >
                                        <?php for ($i = 1; $i <= max($day, 4); $i++) {
                                            $tau = "tau" . $i;
                                            echo "<td>" . $row[$tau] . "</td>";
                                        } ?>
                                    </tr>

    


                            </table>
                        



                    </div>


                    <div class="statw">

                        <table>

                            <tr class=" stat0">
                                <td>Time</td>
                                <td>Trader ID</td>
                                <td>Stock</td>
                                <td>S/B</td>
                                <td>Number</td>
                                <td>Price</td>
                                <td>State</td>
                            </tr>

                            <tr style="background-color: transparent;">
                                <td colspan="7"></td>

                            </tr>

                            <?php
                            $sql1 = "SELECT * FROM op_history WHERE trader = $id ORDER BY op_nbr DESC";
                            $result1 = mysqli_query($conn, $sql1);
                            while ($row1 = $result1->fetch_assoc()) {

                                if ($row1['done'] == 0) {
                                    $state = "assets/icons/o.png";
                                    $alt = "waiting";
                                } elseif ($row1['done'] == -1) {
                                    $state = "assets/icons/x.png";
                                    $alt = "failde";
                                } else {
                                    $state = "assets/icons/done.png";
                                    $alt = "done";
                                }
                                if ($row1['SB'] == "S") {
                                    $sb = "SELL";
                                } else {
                                    $sb = "BUY";
                                }

                            ?>


                                <tr class=" stat1">
                                    <td><?= $row1['ttime'] ?></td>
                                    <td><?= $row1['trader'] ?></td>
                                    <td><?= $row1['stock'] ?></td>
                                    <td><?= $sb ?></td>
                                    <td><?= $row1['nbr'] ?></td>
                                    <td><?= $row1['price'] ?></td>
                                    <td><img class="staticons" src="<?= $state ?>" alt="<?= $alt ?>"></td>
                                </tr>

                            <?php } ?>
                        </table>
                    </div>
                </section>
            </div>
        <?php } ?>

        </div>
        <script src="js/fa/all.js"></script>

</body>

</html>