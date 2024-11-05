<?php
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';
include 'db.php';

$q = "select rfid_id from rfid_history order by time desc limit 1";
$r = mysqli_query($conn, $q);
$rfid = mysqli_fetch_assoc($r);

if ($rfid != null) {
    $rfid_id = $rfid['rfid_id'];
    // echo $rfid_id;


    $q = "select * from siswa where rfid_id='$rfid_id'";
    $r = mysqli_query($conn, $q);
    $siswa = mysqli_fetch_assoc($r);
    if ($siswa == null) {
        echo "<script>alert('Kartu RFID tidak terdaftar!')</script>";
        echo "<script>location.href='absensi.php'</script>";
    }

    $nisn = $siswa['nisn'];
    $nis = $siswa['nis'];
    $nama = $siswa['nama'];
    $kelas = $siswa['kelas'];
} else {
    $nisn = "";
    $nis = "";
    $nama = "";
    $kelas = "";
    $rfid_id = "";
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $q = "select * from absen where rfid_id='$rfid_id' and date(time)=curdate()";
    $r = mysqli_query($conn, $q);
    if (mysqli_num_rows($r) > 0) {
        echo "<script>alert('Anda sudah absen hari ini')</script>";
        echo "<script>location.href='absensi.php'</script>";
    } else {
        $q = "insert into absen(nisn) values('$nisn')";
        $r = mysqli_query($conn, $q);
        if ($r) {
            $q1 = "delete from rfid_history where rfid_id='$rfid_id'";
            $r1 = mysqli_query($conn, $q1);
            if ($r1) {
                echo "<script>alert('Berhasil absen')</script>";
                echo "<script>location.href='absensi.php'</script>";
            } else {
                echo "<script>alert('Gagal absen')</script>";
                echo "<script>location.href='absensi.php'</script>";
            }
        } else {
            echo "<script>alert('Gagal absen')</script>";
            echo "<script>location.href='absensi.php'</script>";
        }
    }
}


?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Form Absen</h6>
        </div>
        <div class="card-body">
            <form id="autoPostForm" method="POST" action="">
                <div class="mb-3">
                    <label for="nisn" class="form-label">NISN</label>
                    <input type="text" class="form-control" name="nisn" value="<?php echo $nisn ?>" id="nisn" disabled>
                </div>
                <div class="mb-3">
                    <label for="nis" class="form-label">NIS</label>
                    <input type="text" class="form-control" name="nis" value="<?php echo $nis ?>" id="nis" disabled>
                </div>
                <div class="mb-3">
                    <label for="rfid_id" class="form-label">RFID ID</label>
                    <input type="text" class="form-control" name="rfid_id" value="<?php echo $rfid_id ?>" id="rfid_id" disabled>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" value="<?php echo $nama ?>" id="nama" disabled>
                </div>
                <div class="mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" class="form-control" name="kelas" value="<?php echo $kelas ?>" id="kelas" disabled>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let result = confirm("Tap kartu RFID terlebih dahulu melakukan absensi!");
    if (result) {
        console.log("Tap kartu RFID");
    } else {
        console.log("Cancel");
    }
</script>
<script>
    // Fungsi untuk submit form secara otomatis setelah beberapa detik
    function autoSubmitForm() {
        setTimeout(function() {
            document.getElementById("autoPostForm").submit(); // Submit form
        }, 5000); // Tunda selama 5 detik (5000 milidetik)
    }


    window.onload = autoSubmitForm;
</script>

</body>

</html>