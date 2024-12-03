<?php
include 'db.php';

// Pastikan konten respons berupa JSON
header('Content-Type: application/json');

if ($_SERVER['CONTENT_TYPE'] !== 'application/x-www-form-urlencoded') {
    echo json_encode(['status' => 'Error', 'message' => 'Invalid Content-Type']);
    exit;
}
// Validasi metode request dan parameter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rfid'])) {
    if (!$conn) {
        echo json_encode(['status' => 'Error', 'message' => 'Koneksi database gagal: ' . mysqli_connect_error()]);
        exit;
    }

    // Ambil dan validasi input RFID
    $rfid = trim($_POST['rfid']);
    if (empty($rfid)) {
        echo json_encode(['status' => 'Error', 'message' => 'RFID tidak boleh kosong']);
        exit;
    }

    try {
        // Cek apakah RFID sudah ada di database
        $q = "SELECT * FROM rfid_history WHERE rfid_id = ?";
        $stmt = mysqli_prepare($conn, $q);
        mysqli_stmt_bind_param($stmt, "s", $rfid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Jika RFID ada, perbarui timestamp
            $q = "UPDATE rfid_history SET time = CURRENT_TIMESTAMP WHERE rfid_id = ?";
        } else {
            // Jika RFID belum ada, masukkan data baru
            $q = "INSERT INTO rfid_history (rfid_id, time) VALUES (?, CURRENT_TIMESTAMP)";
        }

        $stmt = mysqli_prepare($conn, $q);
        mysqli_stmt_bind_param($stmt, "s", $rfid);
        mysqli_stmt_execute($stmt);

        // Kirimkan respons berdasarkan hasil eksekusi
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = mysqli_num_rows($result) > 0 ? 'RFID Berhasil Diperbarui' : 'RFID Berhasil Ditambahkan';
            echo json_encode(['status' => 'Success', 'message' => $message]);
        } else {
            echo json_encode(['status' => 'Error', 'message' => 'Gagal memproses RFID']);
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo json_encode(['status' => 'Error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'Error', 'message' => 'Invalid request']);
}

// Tutup koneksi database
mysqli_close($conn);
?>
