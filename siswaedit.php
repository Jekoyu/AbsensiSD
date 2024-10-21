<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';

// Ketika NISN dikirim untuk mengedit data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $nisn = $_POST['nisn'];
    $q = "SELECT * FROM siswa WHERE nisn='$nisn'";
    $result = $conn->query($q);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nisn = $row['nisn'];
        $nis = $row['nis'];
        $rfid_id = $row['rfid_id'];
        $nama = $row['nama'];
        $kelas = $row['kelas'];
    } else {
        echo "Data siswa tidak ditemukan.";
        exit();
    }
}

// Jika form untuk update data dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_data') {
    // Escape data dari form untuk menghindari SQL Injection
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);
    $rfid_id = mysqli_real_escape_string($conn, $_POST['rfid_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);

    // Query update data
    $q = "UPDATE siswa SET nis='$nis', rfid_id='$rfid_id', nama='$nama', kelas='$kelas' WHERE nisn='$nisn'";
    if (mysqli_query($conn, $q)) {
        echo "<script>alert('Data siswa berhasil diubah'); window.location.href='siswa.php';</script>";
        exit();
    } else {
        echo "Error mengubah data siswa: " . mysqli_error($conn);
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 col-lg-7 text-center">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Data Siswa</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="siswaedit.php">
                        <input type="hidden" name="action" value="update_data">
                        <div class="row mb-3">
                            <label for="NISN" class="col-sm-2 col-form-label">NISN</label>
                            <div class="col-sm-10">
                                <input type="text" name="nisn" class="form-control" id="NISN" value="<?php echo $nisn ?>" readonly required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="NIS" class="col-sm-2 col-form-label">NIS</label>
                            <div class="col-sm-10">
                                <input type="text" name="nis" class="form-control" value="<?php echo $nis ?>" id="NIS" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="rfid" class="col-sm-2 col-form-label">RFID</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="rfid_id" id="rfid_id" value="<?php echo $rfid_id ?>" readonly required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-2 col-form-label">NAMA</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama" value="<?php echo $nama ?>" id="nama" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kelas" class="col-sm-2 col-form-label">KELAS</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="kelas" value="<?php echo $kelas ?>" id="kelas" required>
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
mysqli_close($conn);
include 'slicing/footer.php';
include 'slicing/script.php';
?>