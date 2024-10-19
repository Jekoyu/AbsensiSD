<?php

include 'db.php';

$sql = "select * from rfid_history ORDER BY time DESC limit 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data = [
            'rfid_id' => $row["rfid_id"],
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
