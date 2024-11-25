<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = $_POST['nisn'];
    $status = $_POST['status'];

    // Periksa apakah siswa sudah ada di tabel absen
    $check_query = "SELECT * FROM absen WHERE nisn = '$nisn'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // Jika siswa sudah ada di absen, update status
        $update_query = "UPDATE absen SET status = '$status' WHERE nisn = '$nisn'";
        if ($conn->query($update_query)) {
            echo "Status updated successfully";
        } else {
            echo "Error updating status: " . $conn->error;
        }
    } else {
        // Jika siswa belum ada di tabel absen, tambahkan sebagai data baru
        $insert_query = "INSERT INTO absen (nisn, status, tanggal) VALUES ('$nisn', '$status', CURDATE())";
        if ($conn->query($insert_query)) {
            echo "Status inserted successfully";
        } else {
            echo "Error inserting status: " . $conn->error;
        }
    }
}
