<?php
include 'db.php';  // Untuk koneksi ke database
include 'env.php'; // Untuk pengaturan environment
include 'slicing/head.php';
include 'slicing/nav.php';
include 'slicing/topbar.php';

// Ambil nilai dari GET dan validasi
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : null;
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : null;

// Fungsi untuk mendapatkan nama bulan
function getNamaBulan($bulan)
{
    $namaBulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    $bulanAngka = substr($bulan, 5, 2);
    return isset($namaBulan[$bulanAngka]) ? $namaBulan[$bulanAngka] : 'Bulan Tidak Valid';
}

// Fungsi untuk mendapatkan jumlah hari dalam bulan
function getJumlahHari($bulan)
{
    if ($bulan) {
        $tahun = (int)substr($bulan, 0, 4);
        $bulanAngka = (int)substr($bulan, 5, 2);
        return cal_days_in_month(CAL_GREGORIAN, $bulanAngka, $tahun);
    }
    return 0; // Jika bulan tidak valid
}

// Ambil nama bulan dan jumlah hari
$namaBulan = $bulan ? getNamaBulan($bulan) : 'Tidak Dipilih';
$jumlahHari = $bulan ? getJumlahHari($bulan) : 0;

// Query untuk mengambil semua siswa dalam kelas yang dipilih
$query = "SELECT nisn, nama FROM siswa WHERE kelas = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('MySQL prepare failed: ' . $conn->error);  // Menampilkan error jika prepare gagal
}
$stmt->bind_param("s", $kelas); // "s" untuk string
$stmt->execute();
$result = $stmt->get_result();
$siswa = $result->fetch_all(MYSQLI_ASSOC);

// Ambil data absensi siswa dalam bulan yang dipilih
$absensiQuery = "SELECT nisn, tanggal, status FROM absen WHERE DATE_FORMAT(tanggal, '%Y-%m') = ?";
$absensiStmt = $conn->prepare($absensiQuery);
if ($absensiStmt === false) {
    die('MySQL prepare failed: ' . $conn->error);  // Menampilkan error jika prepare gagal
}
$absensiStmt->bind_param("s", $bulan); // "s" untuk string
$absensiStmt->execute();
$absensiResult = $absensiStmt->get_result();

// Group absensi berdasarkan nisn dan tanggal
$absensiData = [];
while ($row = $absensiResult->fetch_assoc()) {
    $absensiData[$row['nisn']][$row['tanggal']] = $row;
}

?>

<div class="container-fluid mt-4">
    <h3 class="text-center mb-4">
        Data Kehadiran Kelas <?php echo htmlspecialchars($kelas); ?> - Bulan <?php echo htmlspecialchars($namaBulan); ?>
    </h3>
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Rekap Absensi - Bulan: <?php echo htmlspecialchars($namaBulan); ?>
            </h6>
            <button id="downloadPDF" class="btn btn-success">
                Print to PDF
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover text-center align-middle" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <th colspan="<?php echo $jumlahHari; ?>">Tanggal</th>
                            <th colspan="3">Jumlah</th>
                            <th rowspan="2">Total</th>
                        </tr>
                        <tr>
                            <?php for ($i = 1; $i <= $jumlahHari; $i++): ?>
                                <th><?php echo $i; ?></th>
                            <?php endfor; ?>
                            <th>S</th>
                            <th>I</th>
                            <th>H</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($siswa as $s):
                            $nisn = $s['nisn'];
                            $kehadiranS = 0;
                            $kehadiranI = 0;
                            $kehadiranH = 0;
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($s['nama']); ?></td>
                                <?php
                                // Menampilkan status kehadiran per hari
                                // Menampilkan status kehadiran per hari
                                for ($i = 1; $i <= $jumlahHari; $i++):
                                    $tanggal = sprintf('%04d-%02d-%02d', substr($bulan, 0, 4), substr($bulan, 5, 2), $i);

                                    // Jika absensi ada, tampilkan statusnya, jika tidak ada, anggap "-".
                                    if (isset($absensiData[$nisn][$tanggal])) {
                                        $status = $absensiData[$nisn][$tanggal]['status'];

                                        // Ganti 'hadir' menjadi 'H'
                                        if ($status == 'hadir') {
                                            $status = 'H';
                                        } else if ($status == 'sakit') {
                                            $status = 'S';
                                        } else if ($status == 'izin') {
                                            $status = 'I';
                                        }
                                    } else {
                                        $status = '-'; // Ganti Alpa (A) dengan tanda "-"
                                    }

                                    // Hitung jumlah S, I, H
                                    if ($status == 'S') $kehadiranS++;
                                    if ($status == 'I') $kehadiranI++;
                                    if ($status == 'H') $kehadiranH++;
                                ?>
                                    <td><?php echo $status; ?></td>
                                <?php endfor; ?>


                                <td><?php echo $kehadiranS; ?></td>
                                <td><?php echo $kehadiranI; ?></td>
                                <td><?php echo $kehadiranH; ?></td>
                                <td><?php echo $kehadiranS + $kehadiranI + $kehadiranH; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("downloadPDF").addEventListener("click", function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('landscape');  // Menentukan orientasi Landscape

        // Menambahkan judul utama di tengah (SD Ngemplak)
        doc.setFontSize(18);
        doc.setFont("helvetica", "bold");
        const pageWidth = doc.internal.pageSize.getWidth(); // Lebar halaman
        const title = "SD Negeri Ngemplak Nganti";
        const titleWidth = doc.getTextWidth(title);
        doc.text(title, (pageWidth - titleWidth) / 2, 15); // Posisi tengah

        // Menambahkan subjudul (Kelas dan Bulan)
        doc.setFontSize(14);
        doc.setFont("helvetica", "normal");
        const subTitle = "Rekap Absensi Kelas: <?php echo htmlspecialchars($kelas); ?> - Bulan: <?php echo htmlspecialchars($namaBulan); ?>";
        const subTitleWidth = doc.getTextWidth(subTitle);
        doc.text(subTitle, (pageWidth - subTitleWidth) / 2, 25); // Posisi tengah sedikit di bawah judul

        // Menangkap tabel untuk dimasukkan ke PDF
        const table = document.getElementById("dataTable");

        // Menambahkan tabel dengan penyesuaian styling
        doc.autoTable({
            html: table,
            startY: 35,  // Mulai di bawah judul
            theme: 'grid',  // Tema tabel grid dengan garis tegas
            headStyles: {
                fillColor: [200, 200, 200],  // Warna latar header abu-abu
                textColor: [0, 0, 0],  // Warna teks header hitam
                fontSize: 10,  // Ukuran font header
                halign: 'center',  // Rata tengah teks header
            },
            bodyStyles: {
                fontSize: 8,  // Ukuran font body tabel
                valign: 'middle',  // Vertikal align
            },
            styles: {
                lineWidth: 0.5,  // Ketebalan garis tabel
                lineColor: [0, 0, 0],  // Warna garis tabel hitam
            },
            margin: { top: 10, left: 10, right: 10 },  // Margin tabel
        });

        // Preview PDF di tab baru
        window.open(doc.output('bloburl'), '_blank');
    });
</script>




<?php
include 'slicing/footer.php';
include 'slicing/script.php';

// Menutup koneksi MySQLi
$conn->close();
?>