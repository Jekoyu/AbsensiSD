<?php
include 'env.php';
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';
include 'db.php';
date_default_timezone_set('Asia/Jakarta');
function tanggal_indo($tanggal)
{
  $bulan = array(
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $split = explode('-', $tanggal);
  return $split[2] . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];
}
?>


<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <h6 class="h5 mb-0 text-gray-800">
      <?php
      echo tanggal_indo(date('Y-m-d'));
      ?>
    </h6>
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $q = "select nisn  from siswa";
                $result = $conn->query($q);
                echo $result->num_rows;

                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Siswa Hadir Hari Ini</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $today = getdate(date("U"));
                $t = "$today[year]-$today[mon]-$today[mday]";
                // echo $t;
                $q = "select nisn from absen where date(tanggal)='$t' && status='hadir'";
                $result = $conn->query($q);
                echo $result->num_rows;
                // $conn->close();          
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kehadiran (Hari Ini)</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                    <?php
                    $today = getdate(date("U"));
                    $t = "$today[year]-$today[mon]-$today[mday]";
                    $q = "SELECT nisn FROM absen WHERE DATE(tanggal)='$t' AND status='hadir'";
                    $result = $conn->query($q);
                    $i = $result->num_rows;

                    $qe = "SELECT nisn FROM siswa";
                    $rs = $conn->query($qe);
                    $all = $rs->num_rows;

                    if ($all != 0) {
                      $val = number_format((100 / $all * $i), 2); // Persentase dengan 2 angka di belakang koma
                      echo $val . "%";
                    } else {
                      $val = 0;
                      echo $val . "%";
                    }
                    ?>
                  </div>
                </div>
                <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width:<?php echo $val; ?>%"
                      aria-valuenow="<?php echo $val; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian Bulan Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kehadiran (Bulan Ini)</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                    <?php
                    $y = "$today[year]";
                    $m = "$today[mon]";
                    $q = "SELECT nisn FROM absen WHERE MONTH(tanggal)='$m' AND YEAR(tanggal)='$y'";
                    $result = $conn->query($q);
                    $i = $result->num_rows;

                    $qe = "SELECT tanggal FROM absen WHERE YEAR(tanggal)='$y' GROUP BY tanggal";
                    $rs = $conn->query($qe);
                    $all = $rs->num_rows;

                    $qer = "SELECT nisn FROM siswa";
                    $rsa = $conn->query($qer);
                    $sisa = $rsa->num_rows;

                    if ($all * $sisa != 0) {
                      $val = number_format(($i / ($sisa * $all) * 100), 2); // Persentase dengan 2 angka di belakang koma
                    } else {
                      $val = 0;
                    }

                    echo $val . "%";
                    ?>
                  </div>
                </div>
                <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div class="progress-bar bg-warning" role="progressbar" style="width:<?php echo $val; ?>%"
                      aria-valuenow="<?php echo $val; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Content Row -->

    <div class="row">

      <!-- Area Chart -->
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Profil Sekolah</h6>
            <div class="dropdown no-arrow">

            </div>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <img src="img/3.jpeg" width="580" alt="" srcset="">
          </div>
        </div>
      </div>

      <!-- Pie Chart -->
      <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

            <div class="dropdown no-arrow">


            </div>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="text-center">
              <img src="img/tutwuri.png" alt="tutwuri" width="280" height="300" srcset="">
            </div>
          </div>
        </div>
      </div>
    </div>





    <?php
    include 'slicing/footer.php';
    include 'slicing/script.php';
    ?>