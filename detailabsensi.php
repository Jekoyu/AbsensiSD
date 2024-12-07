<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';

$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : null;
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : null;

if (empty($tanggal) || empty($kelas)) {
    echo "<div class='alert alert-danger'>Tanggal dan kelas harus diisi.</div>";
    exit;
}

$tanggal_db = date('Y-m-d', strtotime(str_replace('-', '/', $tanggal)));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ubah_status'])) {
    $nisn = $_POST['nisn'];
    $status = $_POST['status'];

    if ($status === 'alpha') {
        $q = "DELETE FROM absen WHERE nisn = ? AND tanggal = ?";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("ss", $nisn, $tanggal_db);
    } else {
        $q = "
            INSERT INTO absen (nisn, tanggal, status, jam_datang) 
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE status = ?, jam_datang = NOW()
        ";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("ssss", $nisn, $tanggal_db, $status, $status);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Status berhasil diubah!');</script>";
    } else {
        echo "<script>alert('Gagal mengubah status.');</script>";
    }
}
?>

<div class="container-fluid mt-4">
    <h3 class="text-center mb-4">Data Kehadiran Kelas <?php echo $kelas; ?> - Tanggal <?php echo $tanggal; ?></h3>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Jam Datang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = "
                            SELECT siswa.nisn, siswa.nama, siswa.kelas, 
                                   IFNULL(absen.status, 'alpha') AS status,
                                   IFNULL(absen.jam, '-') AS jam
                            FROM siswa
                            LEFT JOIN absen 
                                ON siswa.nisn = absen.nisn 
                                AND absen.tanggal = ?
                            WHERE siswa.kelas = ?
                        ";

                        $stmt = $conn->prepare($q);
                        $stmt->bind_param("ss", $tanggal_db, $kelas);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $status = $row['status'];
                                $status_class = $status === 'hadir' ? 'text-success' :
                                                ($status === 'alpha' ? 'text-danger' : 'text-secondary');
                                $jam_datang = $row['jam'];

                                echo "<tr>";
                                echo "<td>" . $row['nisn'] . "</td>";
                                echo "<td>" . $row['nama'] . "</td>";
                                echo "<td>" . $jam_datang . "</td>";
                                echo "<td><span class='$status_class'>" . ucfirst($status) . "</span></td>";
                                echo "<td>
                                    <button class='btn btn-warning btn-sm btn-ubah' 
                                        data-nisn='" . $row['nisn'] . "' 
                                        data-nama='" . $row['nama'] . "' 
                                        data-status='" . $status . "'>
                                        <i class='fas fa-edit'></i> Ubah
                                    </button>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Tidak ada data kehadiran untuk kelas ini pada tanggal tersebut.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include 'slicing/footer.php';
include 'slicing/script.php';
?>
<script>
    $(document).ready(function () {
        var dataTable = $('#dataTable').DataTable({
            dom: 'Bfrtip',
            paging: false,
            columnDefs: [
                { orderable: false, targets: [4] }
            ],
            buttons: [
                { extend: 'copy', exportOptions: { columns: ':not(:last-child)' }},
                { extend: 'csv', exportOptions: { columns: ':not(:last-child)' }},
                { extend: 'excel', exportOptions: { columns: ':not(:last-child)' }},
                { extend: 'pdf', exportOptions: { columns: ':not(:last-child)' }},
                { extend: 'print', exportOptions: { columns: ':not(:last-child)' }}
            ]
        });

        $('.btn-ubah').on('click', function () {
            var nisn = $(this).data('nisn');
            var nama = $(this).data('nama');
            var status = $(this).data('status');

            $('#modalNisn').val(nisn);
            $('#modalNama').val(nama);
            $('#modalStatus').val(status);
            $('#modalUbahStatus').modal('show');
        });
    });
</script>
