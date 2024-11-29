<?php

$json_url = "https://v2.jokeapi.dev/joke/Any"; 
$xml_url = "https://v2.jokeapi.dev/joke/Any?format=xml"; 

function measureTime(callable $callback) {
    $start_time = microtime(true);
    $callback(); 
    $end_time = microtime(true); 
    return $end_time - $start_time; 
}


$json_time = measureTime(function() use ($json_url) {
    $response = file_get_contents($json_url);
    if ($response !== FALSE) {
        json_decode($response, true); 
    }
});

$xml_time = measureTime(function() use ($xml_url) {
    $response = file_get_contents($xml_url);
    if ($response !== FALSE) {
        simplexml_load_string($response); 
    }
});

echo "Waktu proses JSON: " . $json_time . " detik<br>";
echo "Waktu proses XML: " . $xml_time . " detik<br>";
?>
