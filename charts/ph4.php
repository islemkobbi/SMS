<?php

include '../php/config.php';
error_reporting(0);
session_start();

$stocks = $_SESSION['stocks'];
$snbr = count($stocks);


$sql = "SELECT * FROM properties,users WHERE properties.id = users.id ";
$result = mysqli_query($conn, $sql);

$banks = array();
$bank_totals = array();
$traders = array();
$trader_totals = array();

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

    if ($uid < 3000) {
        array_push($traders, $user);
        array_push($trader_totals, $total);
    } else {
        array_push($banks, $user);
        array_push($bank_totals, $total);
    }
}
$r_bank_totals = $bank_totals;
rsort($r_bank_totals);
$r_trader_totals = $trader_totals;
rsort($r_trader_totals);
?>


<div class="statw" style="padding-top: 0;">
    <table>
        <th colspan="6"> Traders </th>
        <th style="background-color: #dadada;padding:0"></th>
        <th colspan="6"> Banks </th>

        <tr class=" stat0">
            <td>ranking</td>
            <td>Trader id</td>
            <td>Trader name</td>
            <td>Money ($)</td>
            <td>Assets ($)</td>
            <td>Total ($)</td>
            <td style="background-color: #dadada;padding:0"></td>
            <td>ranking</td>
            <td>Bank id</td>
            <td>Bank name</td>
            <td>Money ($)</td>
            <td>Assets ($)</td>
            <td>Total ($)</td>

        </tr>

        <tr style="background-color:#dadada;">
            <td colspan="11"></td>

        </tr>

        <?php
        $ii = 0;
        $jj = 0;
        $rt = 0;
        $rb = 0;
        do {
            $s = true;
            $b = true;
            if ($ii < count($trader_totals)) {

                $t_trader = $r_trader_totals[$ii];
                $i = array_search($t_trader, $trader_totals);
                $trader_totals[$i] = (float) 0;
                $ii += 1;
                $rt += 1;
            } else {
                $traders[$i] = ["", "", "", "", ""];
                $s = false;
                $rt = "";
            }

            if ($jj < count($bank_totals)) {
                $t_bank = $r_bank_totals[$jj];
                $j = array_search($t_bank, $bank_totals);
                $bank_totals[$j] = (float) 0;
                $jj += 1;
                $rb += 1;
            } else {
                $banks[$j] = ["", "", "", "", ""];
                $b = false;
                $rb = "";
            }






            $ss = 'style="background-color:#dadada"';
            if ($s) {
                $ss = "";
            }

            $bb = 'style="background-color:#dadada"';
            if ($b) {
                $bb = "";
            }

        ?>


            <tr class=" stat1">
                <td <?= $ss ?>><?= $rt ?></td>
                <td <?= $ss ?>><?= $traders[$i][0] ?></td>
                <td <?= $ss ?>><?= $traders[$i][1] ?></td>
                <td <?= $ss ?>><?= $traders[$i][2] ?></td>
                <td <?= $ss ?>><?= $traders[$i][3] ?></td>
                <td <?= $ss ?>><?= $traders[$i][4] ?></td>
                <td style="background-color: #dadada;padding:0"></td>
                <td <?= $bb ?>><?= $rb ?></td>
                <td <?= $bb ?>><?= $banks[$j][0] ?></td>
                <td <?= $bb ?>><?= $banks[$j][1] ?></td>
                <td <?= $bb ?>><?= $banks[$j][2] ?></td>
                <td <?= $bb ?>><?= $banks[$j][3] ?></td>
                <td <?= $bb ?>><?= $banks[$j][4] ?></td>


            </tr>

        <?php
        } while ($s or $b); ?>
    </table>
</div>