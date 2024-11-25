<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';

// Aktifkan error reporting untuk menangkap kesalahan
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 col-lg-7 text-center">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Input Siswa Baru</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <label for="NISN" class="col-sm-2 col-form-label">NISN</label>
                            <div class="col-sm-10">
                                <input type="number" name="nisn" class="form-control" id="NISN" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="NIS" class="col-sm-2 col-form-label">NIS</label>
                            <div class="col-sm-10">
                                <input type="number" name="nis" class="form-control" id="NIS" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="rfid" class="col-sm-2 col-form-label">RFID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="rfid_id" id="rfid_id" readonly required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-2 col-form-label">NAMA</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama" id="nama" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kelas" class="col-sm-2 col-form-label">KELAS</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="kelas" id="kelas" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

if (isset($_POST['nis'])) {
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);
    $rfid_id = mysqli_real_escape_string($conn, $_POST['rfid_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);


    $q = "INSERT INTO siswa (NISN, NIS, rfid_id, nama, kelas) VALUES ('$nisn', '$nis', '$rfid_id', '$nama', '$kelas')";
    if (mysqli_query($conn, $q)) {
        echo "Data berhasil ditambahkan";

        $sql = "DELETE FROM rfid_history WHERE rfid_id='$rfid_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: siswa.php");
        } else {
            echo "Error menghapus RFID: " . mysqli_error($conn);
        }
    } else {
        echo "Error menambahkan data siswa: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<script>
    // Ketika halaman dimuat pertama kali
    window.onload = function() {
        // Mengingatkan pengguna untuk tap RFID
        alert('Silakan tap kartu RFID terlebih dahulu sebelum melanjutkan.');

        // Mengambil data RFID dari server
        fetch("http://localhost/riset/rfid/get_rfid.php")
            .then((response) => response.json())
            .then((data) => {
                if (data.rfid_id) {
                    document.getElementById("rfid_id").value = data.rfid_id;
                } else {
                    alert('Tidak ada data RFID yang terdeteksi. Silakan tap ulang kartu RFID.');
                }
            })
            .catch((error) => console.error("Error fetching data:", error));
    };
</script>

<?php
include 'slicing/footer.php';
include 'slicing/script.php';
?>