<?php

include '../php/config.php';
error_reporting(0);
session_start();



$stocks = $_SESSION['stocks'];
$snbr = count($stocks);


if ($_SESSION['sn'] >= $snbr - 1) {
    $_SESSION['sn'] = 0;
} else {
    $_SESSION['sn'] += 1;
}
$i = $_SESSION['sn'];
$stock = $stocks[$i];


$sql = "SELECT ttime,$stock FROM stocks_history ORDER BY nbr DESC LIMIT 100";
$result = mysqli_query($conn, $sql);


$x = array();
$y = array();

while ($row = $result->fetch_assoc()) {
    array_unshift($x, $row['ttime']);
    array_unshift($y, $row[$stock]);
}

$price = round(end($y),4);
$delta = $price - $y[max(count($y)-5,0)];
if ($delta < 0) {
    $color = "rgb(255, 0, 0)";
} elseif ($delta > 0) {
    $color = "rgb(0, 255, 0)";
} else {
    $color = "#fff";
}

?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../style/chartstyle.css">
</head>

<body>
    <script src="../package/dist/chart.js"></script>
    <div style="height:19em;">
        <canvas id="<?= $stock ?>"></canvas>
    </div>
    <script>
        const gradientbg = document.getElementById('<?= $stock ?>').getContext('2d').createLinearGradient(0, 0, 0, 360);
        gradientbg.addColorStop(0, 'rgba(255, 255, 255, 0.5)');
        gradientbg.addColorStop(1, 'transparent');
        var labels = <?= json_encode($x) ?>;

        var data = {
            labels: labels,
            datasets: [{
                label: '<?= $stock ?> :   <?= $price ?> $',
                backgroundColor: gradientbg,
                borderColor: 'rgba(255,255,255,1)',
                data: <?= json_encode($y) ?>,
                tension: 0.1,
                fill: true
            }]
        };

        var config = {
            type: 'line',
            data: data,
            options: {

                maintainAspectRatio: false,



                elements: {
                    point: {
                        radius: 0
                    }
                },

                scales: {
                    x: {
                        ticks: {
                            display: true,
                            autoSkip: true,
                            maxRotation: 0,
                            minRotation: 0,
                            maxTicksLimit: 6
                        },

                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {

                            callback: function(value) {
                                const val = this.getLabelForValue(value);
                                const vald = val.replaceAll(',', '');
                                var valf = parseFloat(vald);
                                if (valf < 1000) {
                                    return valf.toFixed(2);
                                }
                                if (valf > 1000 && valf < 1000000) {
                                    valf = valf / 1000;
                                    return valf.toFixed(0) + 'K';
                                }
                                if (valf > 100000) {
                                    valf = valf / 1000000;
                                    return valf.toFixed(0) + 'M';
                                }
                            }
                        }
                    },

                },

                layout: {
                    padding: 0,
                    margine: 0,
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            boxWidth: 0,
                            color: '<?= $color ?>',
                            font: {
                                size: 30
                            }

                        }
                    }
                }





            }
        };

        const myChart = new Chart(
            document.getElementById("<?= $stock ?>"),
            config
        );
    </script>
</body>

</html>