<?php

include '../php/config.php';
error_reporting(0);
session_start();

?>


<table class="statc">
    <th class="stat0">Stock</th>
    <th class="stat0">Dividend</th>
    <th class="stat0">Number</th>
    <th class="stat0">Price</th>
    <th class="stat0" style="width: 20px; background-color: #dadada;"></th>
    <th class="stat0">Stock</th>
    <th class="stat0">Dividend</th>
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
            <td style="background-color: #dadada;padding:0"></td>

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