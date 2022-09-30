<?php
include 'config.php';
error_reporting(0);
session_start();


$id = $_GET['uid'];

$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nbr = mysqli_num_rows($result);
if ($nbr > 0) {

    $name = $row['fullname'];

    $stocks = array();
    $sql = "SELECT stock FROM stocks";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {
        array_push($stocks, $row['stock']);
    }

?>
    <div class="statw">
        <?php
        $sql = "SELECT money FROM properties WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        ?>
        <table>
            <tr style="background-color: transparent;">
                <td style=" text-align: left;font-size: large; color: #000;">
                    Name : <b> <?= $name ?></b>
                </td>
                <td style="font-size: large; color: #000;">
                    ID : <b> <?= $id ?></b>
                </td>
                <td style="font-size: large; text-align: right; color: #000;">
                    Money : <b contenteditable="true" onblur="update_properties(this,'money',<?= $id ?>)"> <?= $row['money'] ?> </b> $
                </td>
            </tr>
        </table>

        <br><br>
        <p style="font-size: large;">Properties</p>
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
                        <td contenteditable="true" onblur="update_properties(this,'<?= $stocks[$j] ?>',<?= $id ?>)"><?= $row1["$stocks[$j]"] ?></td>
                    <?php } ?>
                </tr>

            <?php $i += $spr;
                $t += 1;
            } ?>
        </table>
    </div>

    <script>
        function update_properties(element, para, id) {
            var value = element.innerText;
            $(element).attr('class', 'loading');
            $.ajax({
                url: 'php/update_properties.php',
                type: 'POST',
                data: {
                    value: value,
                    para: para,
                    id: id
                },
                success: function(php_result) {
                    console.log(php_result);
                    $(element).removeAttr('class');
                }
            })
        }
    </script>


    <div class="statw">
        <p>history</p>
        <table>

            <tr class=" stat0">
                <td>Time</td>
                <td>Trader ID</td>
                <td>Bank ID</td>
                <td>Stock</td>
                <td>S/B</td>
                <td>Number</td>
                <td>Price ($)</td>
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
                    <td><?= $row1['bank'] ?></td>
                    <td><?= $row1['stock'] ?></td>
                    <td><?= $sb ?></td>
                    <td><?= $row1['nbr'] ?></td>
                    <td><?= $row1['price'] ?></td>
                    <td><img class="staticons" src="<?= $state ?>" alt="<?= $alt ?>"></td>
                </tr>

            <?php } ?>
        </table>
    </div>

<?php } else {
    echo '<p style=" text-align: center;font-size: large; color: #000;"><b>user not found</b></p>';
}
