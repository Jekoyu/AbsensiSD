<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nis'])) {
    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $rfid_id = $_POST['rfid_id'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];

    // Cek apakah RFID sudah ada di database
    $check = $conn->prepare("SELECT * FROM siswa WHERE rfid_id = ?");
    $check->bind_param("s", $rfid_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // RFID sudah digunakan
        echo '<script>
                alert("RFID sudah terdaftar untuk siswa lain. Silakan gunakan kartu RFID yang berbeda.");
              </script>';
    } else {
        // Menambahkan data siswa
        $q = $conn->prepare("INSERT INTO siswa (NISN, NIS, rfid_id, nama, kelas) VALUES (?, ?, ?, ?, ?)");
        $q->bind_param("sssss", $nisn, $nis, $rfid_id, $nama, $kelas);
        if ($q->execute()) {
            // Menghapus data RFID dari history
            $sql = $conn->prepare("DELETE FROM rfid_history WHERE rfid_id = ?");
            $sql->bind_param("s", $rfid_id);
            if ($sql->execute()) {
                echo '<script>
                        alert("Data berhasil ditambahkan!");
                        window.location.href = "siswa.php"; 
                      </script>';
            } else {
                echo "Error menghapus RFID dari history: " . $conn->error;
            }
        } else {
            echo "Error menambahkan data siswa: " . $conn->error;
        }

        $q->close();
        $sql->close();
    }

    $check->close();
}

mysqli_close($conn);
?>

<script>
    window.onload = function() {
        alert('Silakan tap kartu RFID terlebih dahulu sebelum melanjutkan.');
        fetch("http://localhost/AbsensiSD/get_rfid.php")
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
