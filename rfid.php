<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rfid'])) {
    $q = "select * from rfid_history where rfid_id = :rfid";
    $stmt = $pdo->prepare($q);
    $stmt->execute(['rfid' => $_POST['rfid']]);
    $result = $stmt->fetch();
    if ($result) {
        echo json_encode(['status' => 'Warning', 'message' => 'RFID Sudah Ada']);
    } else {
        $q  = "insert into rfid_history (rfid_id) values (:rfid)";
        $stmt = $pdo->prepare($q);
        $stmt->execute(['rfid' => $_POST['rfid']]);
        echo json_encode(['status' => 'Success', 'message' => 'RFID Berhasil Ditambahkan']);
    }
}
