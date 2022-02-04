<?php
//shell_exec('curl -i -F file=@$file localhost:9000/scan');

$folderPath  = "/home/mchabrier/Documents/my_ClamAV-Project/upload";
$allFiles = scandir($folderPath);
$files = array_diff($allFiles, array('.', '..'));

foreach ($files as $file) {
    $filePath  = "$folderPath/$file";

    $ch = curl_init('clamav.atgpedi.net:9000/scan');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);

    $filePath = curl_file_create($filePath);
    $postFields = array('file' => $filePath,);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);



    $curl_result = str_replace('Description', '"Description"', $curl_result);
    $curl_result = str_replace('Status', '"Status"', $curl_result);
    $curl_result = json_decode($curl_result);

    
    echo ($result);
    echo nl2br("\n");
    curl_close($ch);
}
