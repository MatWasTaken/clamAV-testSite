<?php $starttime = microtime(true) ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/png" href="https://www.clamav.net/assets/favicon.ico" />
    <title>Complete output</title>
</head>

<body>
    <header>
        <?php include 'templates/header.php' ?>
    </header>
    <main>
        <h1>Scan</h1>
        <div class="scan">
            <?php include 'back/completeScan.php'; ?>
        </div>
        <br>
        <?php
        $endtime = microtime(true);
        printf("Page loaded in " . round($endtime - $starttime, 3) . "seconds\n",);
        echo nl2br("\n");
        ?>
    </main>
</body>

</html>
