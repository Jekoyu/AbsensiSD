<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';

// Tangkap parameter dari form
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : null;
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : null;

// Validasi input
if (empty($tanggal) || empty($kelas)) {
    echo "<div class='alert alert-danger'>Tanggal dan kelas harus diisi.</div>";
    exit;
}

// Ubah format tanggal ke format database (Y-m-d)
$tanggal_db = date('Y-m-d', strtotime(str_replace('-', '/', $tanggal)));

// Proses ubah status (jika ada permintaan POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ubah_status'])) {
    $nisn = $_POST['nisn'];
    $status = $_POST['status'];

    if ($status === 'alpha') {
        // Hapus data jika status diubah menjadi alpha
        $q = "DELETE FROM absen WHERE nisn = ? AND tanggal = ?";
        $stmt = $conn->prepare($q);
        $stmt->bind_param("ss", $nisn, $tanggal_db);
    } else {
        // Update atau tambahkan data jika status selain alpha
        $q = "
            INSERT INTO absen (nisn, tanggal, status) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE status = ?
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
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query data kehadiran
                        $q = "
                            SELECT siswa.nisn, siswa.nama, siswa.kelas, 
                                   IFNULL(absen.status, 'alpha') AS status
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
                                echo "<tr>";
                                echo "<td>" . $row['nisn'] . "</td>";
                                echo "<td>" . $row['nama'] . "</td>";
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
                            echo "<tr><td colspan='4'>Tidak ada data kehadiran untuk kelas ini pada tanggal tersebut.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Status -->
<div class="modal fade" id="modalUbahStatus" tabindex="-1" role="dialog" aria-labelledby="ubahStatusLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formUbahStatus" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahStatusLabel">Ubah Status Kehadiran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="nisn" id="modalNisn">
                    <input type="hidden" name="ubah_status" value="1">
                    <div class="form-group">
                        <label for="modalNama">Nama Siswa</label>
                        <input type="text" id="modalNama" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modalStatus">Status Kehadiran</label>
                        <select name="status" id="modalStatus" class="form-control">
                            <option value="hadir">Hadir</option>
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'slicing/footer.php';
include 'slicing/script.php';
?>
<script>
    $(document).ready(function () {
        // Inisialisasi DataTable tanpa pagination
        var dataTable = $('#dataTable').DataTable({
            dom: 'Bfrtip',
            paging: false, // Nonaktifkan pagination
            columnDefs: [
                { orderable: false, targets: [ 3] } // Kolom Aksi tidak bisa diurutkan
            ],
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'csv',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'excel',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'pdf',
                    exportOptions: { columns: ':not(:last-child)' }
                },
                {
                    extend: 'print',
                    exportOptions: { columns: ':not(:last-child)' }
                }
            ]
        });

        // Event untuk membuka modal
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
