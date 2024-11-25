<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';
?>

<div class="container-fluid">

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-md-3">
            <input type="text" id="filterDate" class="form-control" placeholder="Select Date">
        </div>
        <div class="col-md-2">
            <button id="filter" class="btn btn-primary">Filter</button>
        </div>
    </div>

    <!-- DataTable Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Absen</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Tanggal</th> <!-- Kolom tanggal ditambahkan -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mengambil data siswa beserta absen menggunakan LEFT JOIN
                        $q = "
                            SELECT siswa.nisn, siswa.nama, siswa.kelas, IFNULL(absen.status, 'alpha') AS status, IFNULL(absen.tanggal, '-') AS tanggal
                            FROM siswa
                            LEFT JOIN absen ON siswa.nisn = absen.nisn
                            ORDER BY absen.tanggal DESC
                        ";

                        $result = $conn->query($q);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nisn'] . "</td>";
                                echo "<td>" . $row['nama'] . "</td>";
                                echo "<td>" . $row['kelas'] . "</td>";

                                // Dropdown untuk status dengan warna
                                $status = $row['status'];
                                echo "<td>";
                                echo "<select class='form-control status-dropdown' data-nisn='" . $row['nisn'] . "'>";
                                echo "<option value='hadir' " . ($status == 'hadir' ? "selected" : "") . " style='background-color: green; color: white;'>Hadir</option>";
                                echo "<option value='alpha' " . ($status == 'alpha' ? "selected" : "") . " style='background-color: red; color: white;'>Alpha</option>";
                                echo "<option value='sakit' " . ($status == 'sakit' ? "selected" : "") . " style='background-color: yellow; color: black;'>Sakit</option>";
                                echo "<option value='izin' " . ($status == 'izin' ? "selected" : "") . " style='background-color: blue; color: white;'>Ijin</option>";
                                echo "</select>";
                                echo "</td>";

                                // Kolom Tanggal
                                echo "<td>" . ($row['tanggal'] != '-' ? date('Y-m-d', strtotime($row['tanggal'])) : '-') . "</td>"; // Pastikan format tanggal konsisten

                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
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
    $(document).ready(function() {
        // Initialize DataTables with export buttons
        var table = $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Initialize datepicker for filtering
        $('#filterDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        // Filter button click event
        $('#filter').click(function() {
            var selectedDate = $('#filterDate').val(); // Ambil nilai tanggal yang dipilih

            if (selectedDate != '') {
                // Hapus filter sebelumnya
                $.fn.dataTable.ext.search.pop();

                // Tambah filter baru berdasarkan tanggal
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var rowDate = data[4]; // Tanggal ada di kolom ke-5 (indeks 4)
                    var formatRowDate = rowDate !== '-' ? rowDate.trim() : '';

                    // Cek apakah tanggal di baris sama dengan tanggal yang dipilih
                    return selectedDate === formatRowDate;
                });
            } else {
                // Hapus filter jika tidak ada tanggal yang dipilih
                $.fn.dataTable.ext.search.pop();
            }

            table.draw(); // Update tampilan tabel
        });

        // Ketika status di dropdown berubah
        $('.status-dropdown').change(function() {
            var nisn = $(this).data('nisn');
            var status = $(this).val();

            // AJAX untuk update status
            $.ajax({
                url: 'update_status.php', // File PHP untuk memproses update
                type: 'POST',
                data: {
                    nisn: nisn,
                    status: status
                },
                success: function(response) {
                    alert('Status berhasil diperbarui');
                },
                error: function() {
                    alert('Terjadi kesalahan saat memperbarui status');
                }
            });
        });
    });
</script>

</body>

</html>