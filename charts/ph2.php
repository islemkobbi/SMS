<?php

include '../php/config.php';
error_reporting(0);
session_start();

?>
<table class="statc ph2">
    <th class="stat0">Stock</th>
    <th class="stat0">Price</th>
    <th class="stat0" style="width: 20px; background-color: #dadada;"></th>
    <th class="stat0">Stock</th>
    <th class="stat0">Price</th>
    <th class="stat0" style="width: 20px; background-color: #dadada;"></th>
    <th class="stat0">Stock</th>
    <th class="stat0">Price</th>
    <th class="stat0" style="width: 20px; background-color: #dadada;"></th>
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
            <td style="background-color: #dadada;padding:0"></td>

            <?php
            $row = $result->fetch_assoc();
            $stock = $row['stock'];
            $val = $row['value'];
            ?>

            <td><?= $stock ?></td>
            <td><?= $val ?> $</td>
            <td style="background-color: #dadada;padding:0"></td>

            <?php
            $row = $result->fetch_assoc();
            $stock = $row['stock'];
            $val = $row['value'];
            ?>

            <td><?= $stock ?></td>
            <td><?= $val ?> $</td>
            <td style="background-color: #dadada;padding:0"></td>

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
        <thead>
            <th colspan="4">SELL OFFERS</th>
            <th style="background-color: #dadada;padding:0"></th>
            <th colspan="4">BUY OFFERS</th>
            <tr class=" stat0">
                <td>trader ID</td>
                <td>Stock</td>
                <td>Number</td>
                <td>Price</td>
                <td style="background-color: #dadada;padding:0"></td>
                <td>trader ID</td>
                <td>Stock</td>
                <td>Number</td>
                <td>Price</td>

            </tr>

            <tr style="background-color:#dadada;">
                <td colspan="9"></td>

            </tr>
        </thead>
        <tbody>
            <?php

            
            if(isset($_SESSION['bop'])){
                $bop = $_SESSION['bop'];
            }else{
                $bop = $_SESSION['bop'] = 0;
            }

            if (isset($_SESSION['sop'])) {
                $sop = $_SESSION['sop'];
            } else {
                $sop = $_SESSION['sop'] = 0;
            }


            $sql1 = "SELECT * FROM op_history WHERE done = 0 AND SB = 'S' AND op_nbr > $sop ORDER BY op_nbr ASC ";
            $_SESSION['result1'] = mysqli_query($conn, $sql1);
            $nt1 = mysqli_num_rows($_SESSION['result1']);

            $sql2 = "SELECT * FROM op_history WHERE done = 0 AND SB = 'B' AND op_nbr > $bop ORDER BY op_nbr ASC";
            $_SESSION['result2'] = mysqli_query($conn, $sql2);
            $nt2 = mysqli_num_rows($_SESSION['result2']);

            if (!isset($_SESSION['nt'])){
                $_SESSION['nt'] = max($nt1, $nt2);
            }
            
            
            #echo $_SESSION['sop'] , "-" , $_SESSION['bop'] ,"------", $_SESSION['nt'],"--------";

            $rnbr = 10 ;#######################################################################"

            if($_SESSION['nt'] > $rnbr){
                $_SESSION['nt'] = $_SESSION['nt'] - $rnbr;
            } else {
                $_SESSION['bop'] = 0;
                $_SESSION['sop'] = 0;
                unset($_SESSION['nt']);
            }


            $s = $row1 = $_SESSION['result1']->fetch_assoc();
            $b = $row2 = $_SESSION['result2']->fetch_assoc();

            $i = 0 ;

            while (($s or $b) and $i < $rnbr) {
                $i = $i + 1;
                $ss = 'style="display:none"';
                if ($s) {
                    $ss = "";
                }
                
                $bb = 'style="display:none"';
                if ($b) {
                    $bb = "";
                }

            ?>


                <tr class=" stat1">

                    <td <?= $ss ?>><?= $row1['trader'] ?></td>
                    <td <?= $ss ?>><?= $row1['stock'] ?></td>
                    <td <?= $ss ?>><?= $row1['nbr'] ?></td>
                    <td <?= $ss ?>><?= $row1['price'] ?></td>
                    <td style="background-color: #dadada;padding:0"></td>
                    <td <?= $bb ?>><?= $row2['trader'] ?></td>
                    <td <?= $bb ?>><?= $row2['stock'] ?></td>
                    <td <?= $bb ?>><?= $row2['nbr'] ?></td>
                    <td <?= $bb ?>><?= $row2['price'] ?></td>


                </tr>

            <?php
                if ($i == $rnbr-1) {
                    $s = $row1 = $_SESSION['result1']->fetch_assoc();
                    $b = $row2 = $_SESSION['result2']->fetch_assoc();
                    $_SESSION['sop'] = $row1['op_nbr'];
                    $_SESSION['bop'] = $row2['op_nbr']; 
                }else{
                    $s = $row1 = $_SESSION['result1']->fetch_assoc();
                    $b = $row2 = $_SESSION['result2']->fetch_assoc();
                }

                
                 
            }?>
        </tbody>
    </table>
</div>