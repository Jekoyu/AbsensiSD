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
            $q = "select * from siswa";
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
                    <a href="edit.php?id=<?php echo $row['nisn'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete.php?id=<?php echo $row['nisn'] ?>" class="btn btn-danger">Delete</a>
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

include 'slicing/footer.php';
include 'slicing/script.php'; ?>


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