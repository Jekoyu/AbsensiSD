<?php
include 'db.php';
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';
?>

<div class="container-fluid mt-4">
    <h3 class="text-center mb-4">Pilih Data Kelas dan Bulan</h3>
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <form id="filterForm" action="rekap.php" method="GET">
                <div class="mb-3">
                    <label for="monthpicker" class="form-label">Pilih Bulan:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="month" id="monthpicker" name="bulan" class="form-control"
                            placeholder="Pilih bulan" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="kelas" class="form-label">Pilih Kelas:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-school"></i></span>
                        <select id="kelas" name="kelas" class="form-control" required>
                            <option value="" disabled selected>Pilih kelas</option>
                            <option value="1">Kelas 1</option>
                            <option value="2">Kelas 2</option>
                            <option value="3">Kelas 3</option>
                            <option value="4">Kelas 4</option>
                            <option value="5">Kelas 5</option>
                            <option value="6">Kelas 6</option>
                        </select>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary mx-2">
                        <i class="fas fa-eye"></i> Lihat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'slicing/footer.php';
include 'slicing/script.php';
?>
