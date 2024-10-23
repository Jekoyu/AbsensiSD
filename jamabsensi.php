<?php
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';
include 'db.php';

$q = "select * from jam_absen where id=1";
$r = mysqli_query($conn, $q);
$jam = mysqli_fetch_assoc($r);
$start = $jam['start'];
$end = $jam['end'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start = $_POST['start'];
    $end = $_POST['end'];
    $q = "update jam_absen set start='$start', end='$end' where id=1";
    $r = mysqli_query($conn, $q);
    if ($r) {
        echo "<script>alert('Berhasil mengubah jam absen')</script>";
        echo "<script>location.href='jamabsensi.php'</script>";
    } else {
        echo "<script>alert('Gagal mengubah jam absen')</script>";
        echo "<script>location.href='jamabsensi.php'</script>";
    }
}
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Jam Absen</h6>
        </div>
        <div class="card-body text">
            <form method="POST">
                <div class="mb-3">
                    <label for="start" class="form-label">Batas Awal</label>
                    <input type="time" name="start" value="<?php echo $start ?>" class="form-control" id="startTime" required>
                </div>
                <div class="mb-3">
                    <label for="end" class="form-label">Batas Akhir</label>
                    <input type="time" name="end" class="form-control" value="<?php echo $end ?>" id="endTime" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>