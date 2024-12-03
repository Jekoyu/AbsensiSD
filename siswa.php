<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';
?>

<div class="container-fluid">
  <!-- DataTable Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
      <div class="d-flex gap-2">
        <!-- Filter Kelas -->
        <select id="filterKelas" class="form-control form-select-sm" style="width: auto;">
          <option value="">Pilih Kelas</option>
          <option value="1">Kelas 1</option>
          <option value="2">Kelas 2</option>
          <option value="3">Kelas 3</option>
          <option value="4">Kelas 4</option>
          <option value="5">Kelas 5</option>
          <option value="6">Kelas 6</option>
        </select>
        <!-- Tombol Export -->
       
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>NISN</th>
              <th>NIS</th>
              <th>Nama</th>
              <th>RFID</th>
              <th>Kelas</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $q = "SELECT * FROM siswa ORDER BY kelas DESC";
            $result = $conn->query($q);
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
            ?>
                <tr>
                  <td></td> <!-- Kolom No akan diisi oleh DataTables -->
                  <td><?php echo $row['nisn'] ?></td>
                  <td><?php echo $row['nis'] ?></td>
                  <td><?php echo $row['nama'] ?></td>
                  <td><?php echo $row['rfid_id']?></td>
                  <td><?php echo $row['kelas'] ?></td>
                  <td>
                    <div class="d-flex">
                      <form action="siswaedit.php" method="POST" class="mr-2">
                        <input type="hidden" name="nisn" value="<?php echo $row['nisn']; ?>">
                        <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                      </form>
                      <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                        <input type="hidden" name="delete_nisn" value="<?php echo $row['nisn']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
            <?php
              }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
// Kode untuk menghapus data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_nisn'])) {
  $delete_nisn = $_POST['delete_nisn'];
  $q = "DELETE FROM siswa WHERE nisn='$delete_nisn'";
  if (mysqli_query($conn, $q)) {
    echo "<script>alert('Data siswa berhasil dihapus'); window.location.href='siswa.php';</script>";
    exit();
  } else {
    echo "Error menghapus data siswa: " . mysqli_error($conn);
  }
}

mysqli_close($conn);
include 'slicing/footer.php';
include 'slicing/script.php';
?>

<!-- Page level custom scripts -->
<script>
  $(document).ready(function() {
    // Inisialisasi DataTable
    var dataTable = $('#dataTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'copy',
          exportOptions: { columns: ':not(:last-child):not(:first-child)' }
        },
        {
          extend: 'csv',
          exportOptions: { columns: ':not(:last-child):not(:first-child)' }
        },
        {
          extend: 'excel',
          exportOptions: { columns: ':not(:last-child):not(:first-child)' }
        },
        {
          extend: 'pdf',
          exportOptions: { columns: ':not(:last-child):not(:first-child)' }
        },
        {
          extend: 'print',
          exportOptions: { columns: ':not(:last-child):not(:first-child)' }
        }
      ],
      columnDefs: [
        { orderable: false, targets: [0, 5] } // Kolom No dan Action tidak bisa diurutkan
      ],
      drawCallback: function(settings) {
        var api = this.api();
        // Update nomor urut
        api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
          cell.innerHTML = i + 1;
        });
      }
    });

    // Event Filter Kelas
    $('#filterKelas').on('change', function() {
      var kelas = $(this).val();
      dataTable.column(4).search(kelas).draw();
    });

    
  });
</script>

</body>
</html>
