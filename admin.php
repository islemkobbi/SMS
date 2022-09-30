<?php

include 'php/config.php';
error_reporting(0);
session_start();

if (!isset($_SESSION["a"])) {
    $_SESSION["a"] = true;
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];

    $pw = '00';
    $_SESSION["a"] = true;

    if ($password == $pw) {
        $_SESSION["a"] = false;
    } else {
        echo "<script>alert('Password Not Matched.')</script>";
    }
}

if (!isset($_SESSION['ref_rate'])) {
    $_SESSION['ref_rate'] = 300000;
}

$sql = "SELECT * FROM _admin LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$phase = $row['phase'];
$day = $row['day'];
$trader_cap = $row['trader_cap'];
$bank_cap = $row['bank_cap'];
$newspaper_price = $row['newspaper_price'];
$cnr_price = $row['cnr_price'];
$ref_rate = $row['ref_rate'];


$stocks = array();
$sql = "SELECT stock FROM stocks";
$result = mysqli_query($conn, $sql);
while ($row = $result->fetch_assoc()) {
    array_push($stocks, $row['stock']);
}
$_SESSION['stocks'] = $stocks;

$sql = "SELECT * FROM properties,users WHERE properties.id = users.id ";
$result = mysqli_query($conn, $sql);
$users = array();
$totals = array();
while ($row = $result->fetch_assoc()) {

    $uid = $row['id'];
    $sql1 = "SELECT * FROM properties WHERE id = $uid LIMIT 1";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);


    $assets = 0;
    foreach ($stocks as $stock) {

        $sql2 = "SELECT value FROM stocks WHERE stock = '$stock' LIMIT 1";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);


        $assets += $row1[$stock] * $row2['value'];
    }
    $total = $assets + $row['money'];
    $user = [$uid, $row['fullname'], $row['money'], $assets, $total];

    array_push($users, $user);
    array_push($totals, $total);
}
$stotals = $totals;
rsort($stotals);

if (!isset($_SESSION['rphase'])) {
    $_SESSION['rphase'] = $phase;
}

if ($_SESSION['rphase'] != $phase) {
    $_SESSION['count'] = 0;
    $_SESSION['rphase'] = $phase;
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
    <title>ADMIN</title>
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
        <!-- sidebar ----------------------------------------------------------------------------- -->
        <div class="sidebar">
            <div class="top-logo">
                <img class="logo1" src="assets/SMS-blanc.png" alt="SMS logo">
            </div>
            <ul class="nav-list">
                <li><a href="#" class="name">
                        <i>
                            <div class="fa-solid fa-bars-progress"></div>
                        </i>
                        <p>ADMIN</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#controlpanel">
                        <i>
                            <div class="fa-solid fa-gear"></div>
                        </i>
                        <p>Control panel</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#stocks">
                        <i>
                            <div class="fa-solid fa-money-bill-trend-up"></div>
                        </i>
                        <p>stocks</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#Banks">
                        <i>
                            <div class="fa-solid fa-building-columns"></div>
                        </i>
                        <p>Banks</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#Traders">
                        <i>
                            <div class="fa-solid fa-user-tie"></div>
                        </i>
                        <p>Traders</p>
                    </a>
                </li>
                <li class="nav-item"><a href="#Propreties">
                        <i>
                            <div class="fa-solid fa-wallet"></div>
                        </i>
                        <p>Propreties</p>
                    </a>
                </li>
                <li class="nav-item"><a href="php/logout_admin.php">
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
            <!-- control panel ----------------------------------------------------------------------------- -->
            <div id="controlpanel" class="section">
                <section>
                    <div class="input-group">
                        <label>phase</label>
                        <select type="text" name="phase" onchange="change_phase(this)">
                            <option value="0" <?php if ($phase == 0) {
                                                    echo "selected";
                                                } ?>>0 - Pause</option>
                            <option value="1" <?php if ($phase == 1) {
                                                    echo "selected";
                                                } ?>>1 - Primery market</option>
                            <option value="2" <?php if ($phase == 2) {
                                                    echo "selected";
                                                } ?>>2 - Secondary market</option>
                            <option value="3" <?php if ($phase == 3) {
                                                    echo "selected";
                                                } ?>>3 - Continuous market</option>
                            <option value="4" <?php if ($phase == 4) {
                                                    echo "selected";
                                                } ?>>4 - After market</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>day</label>
                        <input type="text" placeholder="<?= $day ?>" onblur="change_day(this)">
                    </div>


                    <div class="input-group">
                        <label>refrech_rate (ms) <span id="refresh"></span></label>
                        <input type="text" placeholder="<?= $ref_rate ?>" onblur="change_ref(this)">
                    </div>

                    <div class="input-group" id="f">
                        <button class="btn btn-orange" onclick="fix_price()">fixe price</button>
                        <button class="btn btn-red" style="margin-top: 3px;" onclick="end_day()">end day</button>
                    </div>

                    <div class="input-group">
                        <label>Trader's capital </label>
                        <input type="text" placeholder="<?= $trader_cap ?>" onblur="change_tcap(this)">
                    </div>

                    <div class="input-group">
                        <label>Bank's capital </label>
                        <input type="text" placeholder="<?= $bank_cap ?>" onblur="change_bcap(this)">
                    </div>

                    <div class="input-group">
                        <label>Newspaper price </label>
                        <input type="text" placeholder="<?= $newspaper_price ?>" onblur="change_npp(this)">
                    </div>

                    <div class="input-group">
                        <label> Credit note request price </span></label>
                        <input type="text" placeholder="<?= $cnr_price ?>" onblur="change_cnr(this)">
                    </div>

                </section>
            </div>
            <script>
                function change_phase(element) {
                    var phase = element.value
                    $.ajax({
                        url: 'php/control_panel.php',
                        type: 'POST',
                        data: {
                            phase: phase
                        },
                        success: function(php_result) {
                            console.log(php_result);
                        }
                    })
                    location.reload();

                }

                function change_day(element) {
                    var day = element.value
                    $.ajax({
                        url: 'php/control_panel.php',
                        type: 'POST',
                        data: {
                            day: day
                        },
                        success: function(php_result) {
                            console.log(php_result);
                        }
                    })
                    location.reload();
                }

                function change_tcap(element) {
                    var trader_cap = element.value
                    $.ajax({
                        url: 'php/control_panel.php',
                        type: 'POST',
                        data: {
                            trader_cap: trader_cap
                        },
                        success: function(php_result) {
                            console.log(php_result);
                        }
                    })
                }

                function change_bcap(element) {
                    var bank_cap = element.value
                    $.ajax({
                        url: 'php/control_panel.php',
                        type: 'POST',
                        data: {
                            bank_cap: bank_cap
                        },
                        success: function(php_result) {
                            console.log(php_result);
                        }
                    })
                }

                function change_cnr(element) {
                    var cnr_price = element.value
                    $.ajax({
                        url: 'php/control_panel.php',
                        type: 'POST',
                        data: {
                            cnr_price: cnr_price
                        },
                        success: function(php_result) {
                            console.log(php_result);
                        }
                    })
                }

                function change_npp(element) {
                    var newspaper_price = element.value
                    $.ajax({
                        url: 'php/control_panel.php',
                        type: 'POST',
                        data: {
                            newspaper_price: newspaper_price
                        },
                        success: function(php_result) {
                            console.log(php_result);
                        }
                    })
                }

                function change_ref(element) {
                    var ref_rate = element.value
                    $.ajax({
                        url: 'php/control_panel.php',
                        type: 'POST',
                        data: {
                            ref_rate: ref_rate
                        },
                        success: function(php_result) {
                            console.log(php_result);
                        }
                    })
                    location.reload();
                }



                function fix_price() {
                    $.ajax({
                        url: 'php/fixing.php'
                    })
                    $.ajax({
                        url: 'php/fixing.php'
                    })
                }

                function end_day() {
                    $.ajax({
                        url: 'php/end_day.php'
                    })
                    $.ajax({
                        url: 'php/end_day.php'
                    })
                }
            </script>
            <?php if ($phase == 3 and $day > 0) { ?>
                <script src="package/jquery-latest.js"></script>
                <script>
                    setInterval(function() {
                        $("#refresh").load("php/stock_history.php");
                        console.log('rf');
                    }, <?= $ref_rate ?>)
                </script>
            <?php } ?>
            <script src="package\jquery.min.js"></script>
            <script>
                function update(element, para, stock) {
                    var value = element.innerText

                    console.log(value + ' ' + para + ' ' + stock)
                    $(element).attr('class', 'loading')

                    $.ajax({
                        url: 'php/update_stock.php',
                        type: 'POST',
                        data: {
                            value: value,
                            para: para,
                            stock: stock
                        },
                        success: function(php_result) {
                            console.log(php_result);
                            $(element).removeAttr('class');
                        }
                    })

                }
            </script>
            <!-- end control panel -------------------------------------------------->
            <!-- stocks ------------------------------------------------------------->
            <div id="stocks" class="section">
                <section>
                    <div class="statw">
                        <table>
                            <tr style="background-color: transparent;">
                                <form id="addrem" action="php\add_rem_stocks.php" method="POST">
                                    <td><label>add/remouve stock</label></td>
                                    <td colspan="2"><input type="text" placeholder="stock name" name="stock" required style="margin: 0;padding:0px 10px; width:80%;"></td>
                                    <td><button name="add" class="btn " style="margin: 0; width:100%;">add</button></td>
                                    <td><button name="remove" class="btn btn-red" style="width:100%;">remove</button></td>
                                </form>
                            </tr>
                            <tr style="background-color: transparent;">
                                <td colspan="5"></td>
                            </tr>

                            <th class="stat0">Stock</th>
                            <th class="stat0">benefits</th>
                            <th class="stat0">Number</th>
                            <th class="stat0">Price</th>
                            <th class="stat0">var rate (%)</th>
                            <?php

                            $sql = "SELECT * FROM stocks";
                            $result = mysqli_query($conn, $sql);

                            while ($row = $result->fetch_assoc()) {
                                $stock = $row['stock'];
                                $nbr = $row['nbr'];
                                $val = $row['value'];
                                $rate = $row['rate'];
                                $benefits = $row['benefits'];

                            ?>
                                <tr class="stat1">
                                    <td><?= $stock ?></td>
                                    <td contenteditable="true" onblur="update(this,'benefits','<?= $stock ?>')"><?= $benefits ?></td>
                                    <td contenteditable="true" onblur="update(this,'nbr','<?= $stock ?>')"><?= $nbr ?></td>
                                    <td contenteditable="true" onblur="update(this,'value','<?= $stock ?>')"><?= $val ?></td>
                                    <td contenteditable="true" onblur="update(this,'rate','<?= $stock ?>')"><?= $rate ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </section>
            </div>
            <!-- end stocks -------------------------------------------------->
            <!-- Bancks ------------------------------------------------------------->
            <div id="Banks" class="section">
                <section>
                    <form action="php\admin_add_users.php" method="POST">
                        <div class="input-group">
                            <label>Bank Name</label>
                            <input type="text" placeholder="hamida ben hmida" name="name" required>
                        </div>
                        <div class="input-group">
                            <label>Bank ID</label>
                            <input type="text" placeholder="1052" name="id" required>
                        </div>
                        <div class="input-group">
                            <label>Password</label>
                            <input type="text" placeholder="xxxxxxxx" name="password2" required>
                        </div>
                        <div class="input-group">
                            <button name="usubmit" class="btn" style="display:block;width:100%;">add</button>
                            <button name="rsubmit" class="btn btn-red" style="background-color: red; display:block;width:100%; margin-top:5px">remove</button>
                        </div>
                    </form>
                </section>


                <section>
                    <div class="statw">
                        <table>
                            <tr class=" stat0">
                                <td>Bank id</td>
                                <td>Bank name</td>
                                <td>Money ($)</td>
                                <td>Assets ($)</td>
                                <td>Total ($)</td>
                            </tr>

                            <?php
                            $btotals = $totals;
                            foreach ($stotals as $u) {
                                $i = array_search($u, $btotals);
                                $btotals[$i] = (float) 0;
                                if ($users[$i][0] > 3000) {

                            ?>

                                    <tr class="stat1">
                                        <td><?= $users[$i][0] ?></td>
                                        <td><?= $users[$i][1] ?></td>
                                        <td contenteditable="true" onblur="update_user_money(this,'<?= $users[$i][0] ?>')"><?= $users[$i][2] ?></td>
                                        <td><?= $users[$i][3] ?></td>
                                        <td><?= $users[$i][4] ?></td>
                                    </tr>

                            <?php }
                            } ?>


                        </table>
                    </div>
                </section>

                <section>
                    <div class="statw">
                        <table>
                            <tr class=" stat0">
                                <td>Bank id</td>
                                <?php for ($i = 1; $i <= max($day, 3); $i++) {
                                    echo "<td> intrest rate day " . $i . "</td>";
                                } ?>
                            </tr>

                            <?php
                            $sql = "SELECT * FROM banks";
                            $result = mysqli_query($conn, $sql);
                            while ($row = $result->fetch_assoc()) {
                            ?>

                                <tr class="stat1">
                                    <td><?= $row['id'] ?></td>
                                    <?php for ($i = 1; $i <= max($day, 3); $i++) {
                                        $tau = "tau" . $i;
                                        echo "<td>" . $row[$tau] . "</td>";
                                    } ?>
                                </tr>

                            <?php } ?>


                        </table>
                    </div>
                </section>
            </div>
            <!-- end Bancks ------------------------------------------------------------->
            <!-- Traders ------------------------------------------------------------->
            <div id="Traders" class="section">
                <section>
                    <form action="php\admin_add_users.php" method="POST">
                        <div class="input-group">
                            <label>Trader Name</label>
                            <input type="text" placeholder="hamida ben hmida" name="name" required>
                        </div>
                        <div class="input-group">
                            <label>Trader ID</label>
                            <input type="text" placeholder="1052" name="id" required>
                        </div>
                        <div class="input-group">
                        </div>
                        <div class="input-group">
                            <button name="usubmit" class="btn" style="display:block;width:100%;">add</button>
                            <button name="rsubmit" class="btn btn-red" style="background-color: red; display:block;width:100%; margin-top:5px">remove</button>
                        </div>
                    </form>
                </section>


                <section>
                    <div class="statw">
                        <table>
                            <tr class=" stat0">
                                <td>Trader id</td>
                                <td>Trader name</td>
                                <td>Money ($)</td>
                                <td>Assets</td>
                                <td>Total</td>
                            </tr>

                            <?php
                            $btotals = $totals;
                            foreach ($stotals as $u) {
                                $i = array_search($u, $btotals);
                                $btotals[$i] = (float) 0;
                                if ($users[$i][0] < 3000) {
                            ?>

                                    <tr class="stat1">
                                        <td><?= $users[$i][0] ?></td>
                                        <td><?= $users[$i][1] ?></td>
                                        <td contenteditable="true" onblur="update_user_money(this,'<?= $users[$i][0] ?>')"><?= $users[$i][2] ?></td>
                                        <td><?= $users[$i][3] ?></td>
                                        <td><?= $users[$i][4] ?></td>
                                    </tr>

                            <?php }
                            } ?>
                        </table>
                    </div>
                </section>
            </div>
            <script>
                function update_user_money(element, id) {
                    var value = element.innerText

                    console.log(value + ' ' + id)
                    $(element).attr('class', 'loading')

                    $.ajax({
                        url: 'php/update_user_money.php',
                        type: 'POST',
                        data: {
                            value: value,
                            id: id,
                        },
                        success: function(php_result) {
                            console.log(php_result);
                            $(element).removeAttr('class');
                        }
                    })

                }
            </script>

            <!-- end Traders ------------------------------------------------------------->
            <!-- Propreties ------------------------------------------------------------->
            <div id="Propreties" class="section">
                <section>
                    <div class="input-group">
                        <label>ID </label>
                        <input id="uid" type="text" placeholder="1023">
                    </div>
                    <div class="input-group">
                        <button class="btn" onclick="show_prop()">Show</button>
                        <button class="btn btn-red" onclick="hide_prop()" style="margin-top: 5px;">Hide</button>
                    </div>

                    <div id="uprop"></div>
                </section>
            </div>
            <script>
                function show_prop() {
                    var uid = document.getElementById('uid').value;
                    uid = encodeURIComponent(uid);
                    $("#uprop").load('php/Propreties.php?uid=' + uid);
                    console.log('php/Propreties.php?uid=' + uid);
                }

                function hide_prop() {
                    $("#uprop").empty();
                }
            </script>



        <?php
    }
        ?>
        </div>
        <!-- end content ----------------------------------------------------------------------------- -->
        <script src="js/fa/all.js"></script>
</body>


</html>