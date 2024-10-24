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
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $q = "SELECT * FROM absen";

                            $result = $conn->query($q);
                            ?>
                        </tr>
                        <!-- Additional rows here -->
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

        $('#filterDate').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        // Filter button click event
        $('#filter').click(function() {
            var selectedDate = $('#filterDate').val();

            if (selectedDate != '') {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var date = new Date(data[4]); // Use the 5th column for date (Start date)

                    if (selectedDate == data[4]) { // Compare selected date with the start date in the table
                        return true;
                    }
                    return false;
                });
            }

            table.draw();
        });
    });
</script>

</body>

</html>