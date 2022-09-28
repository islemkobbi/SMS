    <?php

    include '../php/config.php';
    error_reporting(0);
    session_start();

    $stocks = $_SESSION['stocks'];
    $snbr = count($stocks);


    ?>

    <object type="text/html" class="chart" data="charts/chart.php" ?> </object>
    <object type="text/html" class="chart" data="charts/chart.php" ?> </object>

    <div class="statw" >
        <table>
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

            <?php
            $sql1 = "SELECT * FROM op_history WHERE done = 0 AND SB = 'S' ORDER BY op_nbr DESC LIMIT 20";
            $result1 = mysqli_query($conn, $sql1);

            $sql2 = "SELECT * FROM op_history WHERE done = 0 AND SB = 'B' ORDER BY op_nbr DESC LIMIT 20";
            $result2 = mysqli_query($conn, $sql2);

            $s = $row1 = $result1->fetch_assoc();
            $b = $row2 = $result2->fetch_assoc();

            while ($s or $b) {
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
                $s = $row1 = $result1->fetch_assoc();
                $b = $row2 = $result2->fetch_assoc();
            } ?>
        </table>
    </div>