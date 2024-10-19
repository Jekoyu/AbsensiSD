<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rfid'])) {

    $q = "SELECT * FROM rfid_history WHERE rfid_id = ?";
    $stmt = mysqli_prepare($conn, $q);
    mysqli_stmt_bind_param($stmt, "s", $_POST['rfid']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $q =  "update rfid_history set time = CURRENT_TIMESTAMP where rfid_id= ?";
        $stmt = mysqli_prepare($conn, $q);
        mysqli_stmt_bind_param($stmt, "s", $_POST['rfid']);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(['status' => 'Success', 'message' => 'RFID Berhasil Diperbarui']);
        } else {
            echo json_encode([
                'status' => 'Error', 'message' => 'Gagal menambahkan RFID'
            ]);
        }
    } else {
        $q = "INSERT INTO rfid_history (rfid_id) VALUES (?)";
        $stmt = mysqli_prepare($conn, $q);
        mysqli_stmt_bind_param($stmt, "s", $_POST['rfid']);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(['status' => 'Success', 'message' => 'RFID Berhasil Ditambahkan']);
        } else {
            echo json_encode([
                'status' => 'Error', 'message' => 'Gagal menambahkan RFID'
            ]);
        }
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
