<?php
$url = "https://v2.jokeapi.dev/joke/Any";

$response = file_get_contents($url);

if ($response !== FALSE) {
    $data = json_decode($response, true);

    if ($data !== null && isset($data['error']) && !$data['error']) {
        echo "Kategori: " . $data['category'] . "<br>";
        echo "Tipe: " . $data['type'] . "<br>";

        if ($data['type'] == 'single') {
            echo "Lelucon: " . $data['joke'] . "<br>";
        } elseif ($data['type'] == 'twopart') {
            echo "Setup: " . $data['setup'] . "<br>";
            echo "Delivery: " . $data['delivery'] . "<br>";
        }

        echo "Safe: " . ($data['safe'] ? "Yes" : "No") . "<br>";
        echo "Bahasa: " . $data['lang'] . "<br>";
    } else {
        echo "Terjadi kesalahan dalam data JSON atau respons API.";
    }
} else {
    echo "Terjadi kesalahan dalam permintaan API.";
}
?>
