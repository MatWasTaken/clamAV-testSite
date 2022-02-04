<?php

//Variables
$folderPath  = "/home/mchabrier/Documents/my_ClamAV-Project/upload";
$allFiles = scandir($folderPath);
$files = array_diff($allFiles, array('.', '..'));

//Boucles de scan de chaque fichier
foreach ($files as $file) {
    a_un_virus($file, $folderPath);
    echo nl2br("\n");
}


function a_un_virus($fichier, $dossier)
{
    //Appel cURL
    $curl = curl_init("http://clamav.atgpedi.net:9000/scan");
    $fichierCurl = curl_file_create($dossier . "/" . $fichier);
    $postFields = array('file' => $fichierCurl);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 500);
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);

    //Résultat de l'appel cURL
    $curl_result = curl_exec($curl);

    //Si le service ClamAV ne répond pas, on considère que le fichier n'est pas vérolé pour ne pas ralentir le traitement
    if (curl_errno($curl)) {
        return false;
    }

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    echo ($curl_result);
    $curl_result = json_decode($curl_result);
    curl_close($curl);

    //Traduction code HTTP
    switch ($httpcode) {
        case '200':
            return false;
        case '406':
            go_quarantaine($fichier, $dossier);
            return true;
        case '400':
            return false;
        case '412':
            return false;
        case '501':
            return false;
        default:
            return false;
    }
}

function go_quarantaine($fichier, $dossier)
{
    //Appel cURL
    $curl = curl_init("http://clamav.atgpedi.net:9000/upload");
    $fichierCurl = curl_file_create($dossier . "/" . $fichier);
    $postFields = array('file' => $fichierCurl);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 100);
    curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);

    //Résultat de l'appel cURL
    $curl_result = curl_exec($curl);

    //Si le service ClamAV ne répond pas, on considère que le fichier n'est pas vérolé pour ne pas ralentir le traitement
    if (curl_errno($curl)) {
        echo("$fichier non mis en quarantaine : Le serveur antivirus ne répond pas");
        return false;
    }

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    echo($curl_result);
    curl_close($curl);

    switch ($httpcode) {
        case '200':
            echo("$fichier déplacé en quarantaine");
            return false;
        case '406':
            echo("$fichier n'a pas pu être déplacé en quarantaine : erreur 406");
            return true;
        default:
            echo("$fichier non déplacé en quarantaine : erreur inconnue ($httpcode)");
            return false;
    }
}
