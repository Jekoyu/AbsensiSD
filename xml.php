<?php
$url = "https://v2.jokeapi.dev/joke/Any";

$response = file_get_contents($url);

if ($response !== FALSE) {
    header('Content-Type: application/json'); 
    echo $response;
} else {
    echo "Terjadi kesalahan dalam permintaan API.";
}
?>