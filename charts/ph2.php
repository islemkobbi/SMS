<?php

include '../php/config.php';
error_reporting(0);
session_start();

?>
<table class="statc ph2">
    <th class="stat0">Stock</th>
    <th class="stat0">Price</th>
    <th class="stat0" style="width: 20px; background-color: #fff;"></th>
    <th class="stat0">Stock</th>
    <th class="stat0">Price</th>
    <th class="stat0" style="width: 20px; background-color: #fff;"></th>
    <th class="stat0">Stock</th>
    <th class="stat0">Price</th>
    <th class="stat0" style="width: 20px; background-color: #fff;"></th>
    <th class="stat0">Stock</th>
    <th class="stat0">Price</th>


    <?php

    $sql = "SELECT * FROM stocks";
    $result = mysqli_query($conn, $sql);

    while ($row = $result->fetch_assoc()) {
        $stock = $row['stock'];
        $val = $row['value'];

    ?>
        <tr class="stat1">
            <td><?= $stock ?></td>
            <td><?= $val ?> $</td>
            <td style="background-color: #fff;padding:0"></td>

            <?php
            $row = $result->fetch_assoc();
            $stock = $row['stock'];
            $val = $row['value'];
            ?>

            <td><?= $stock ?></td>
            <td><?= $val ?> $</td>
            <td style="background-color: #fff;padding:0"></td>

            <?php
            $row = $result->fetch_assoc();
            $stock = $row['stock'];
            $val = $row['value'];
            ?>

            <td><?= $stock ?></td>
            <td><?= $val ?> $</td>
            <td style="background-color: #fff;padding:0"></td>

            <?php
            $row = $result->fetch_assoc();
            $stock = $row['stock'];
            $val = $row['value'];
            ?>

            <td><?= $stock ?></td>
            <td><?= $val ?> $</td>


        </tr>

    <?php } ?>
</table>



<div class="statw">
    <table>
        <th colspan="4">SELL OFFERS</th>
        <th style="background-color: #fff;padding:0"></th>
        <th colspan="4">BUY OFFERS</th>
        <tr class=" stat0">
            <td>trader ID</td>
            <td>Stock</td>
            <td>Number</td>
            <td>Price</td>
            <td style="background-color: #fff;padding:0"></td>
            <td>trader ID</td>
            <td>Stock</td>
            <td>Number</td>
            <td>Price</td>

        </tr>

        <tr style="background-color: rgb(252, 252, 252);">
            <td colspan="9"></td>

        </tr>

        <?php
        $sql1 = "SELECT * FROM op_history WHERE done = 0 AND SB = 'S' ORDER BY op_nbr DESC LIMIT 20";
        $result1 = mysqli_query($conn, $sql1);

        $sql2 = "SELECT * FROM op_history WHERE done = 0 AND SB = 'B' ORDER BY op_nbr DESC LIMIT 20";
        $result2 = mysqli_query($conn, $sql2);

        $s = $row1 = $result1->fetch_assoc();
        $b = $row2 = $result2->fetch_assoc();
        while ($s or $b) {

        ?>


            <tr class=" stat1">

                <td><?= $row1['trader'] ?></td>
                <td><?= $row1['stock'] ?></td>
                <td><?= $row1['nbr'] ?></td>
                <td><?= $row1['price'] ?></td>
                <td style="background-color: #fff;padding:0"></td>
                <td><?= $row2['trader'] ?></td>
                <td><?= $row2['stock'] ?></td>
                <td><?= $row2['nbr'] ?></td>
                <td><?= $row2['price'] ?></td>


            </tr>

        <?php
            $s = $row1 = $result1->fetch_assoc();
            $b = $row2 = $result2->fetch_assoc();
        } ?>
    </table>
</div>