<?php $starttime = microtime(true) ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="../css/style.css">
    <link rel="icon" type="image/png" href="https://www.clamav.net/assets/favicon.ico" />
    <title>Simple output</title>
</head>

<body>
    <header>
        <?php include 'templates/header.php' ?>
    </header>
    <main>
        <h1>Scan</h1>
        <div class="scan">
            <?php include 'back/scan.php' ?>
        </div>
        <br>
        <?php
        $endtime = microtime(true);
        $loadtime = round($endtime - $starttime, 3);
        printf("Page loaded in $loadtime seconds\n");
        echo nl2br("\n");

        //get files size :
        $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($filePath, FilesystemIterator::SKIP_DOTS));
        foreach ($dir as $file) {
            $size += $file->getSize();
        }
        $kb = $size / 1024;
        echo ("For : $size bytes //" . number_format($kb, 2) . "KB");

        echo nl2br("\n");
        //$kbpersec = (1 / round($endtime - $starttime, 3)) * number_format($size / 1024, 2) . 'Kb/seconde';
        $kbpersec = ($kb / $loadtime)  . 'Kb/seconde';
        echo ("Soit " . number_format($kbpersec,2) . "KB / sec");
        ?>
    </main>
</body>

</html>