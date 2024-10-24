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
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
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
              <th>Kelas</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $q = "SELECT * FROM siswa order by kelas desc";
            $result = $conn->query($q);
            $no = 1;
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
            ?>
                <tr>
                  <td><?php echo $no++ ?></td>
                  <td><?php echo $row['nisn'] ?></td>
                  <td><?php echo $row['nis'] ?></td>
                  <td><?php echo $row['nama'] ?></td>
                  <td><?php echo $row['kelas'] ?></td>
                  <td>
                    <!-- Tombol Edit dan Delete dengan form inline -->
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
    $('#dataTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  });
</script>

</body>

</html>