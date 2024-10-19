<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh Penggunaan Inheritance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Form Input Data Mahasiswa</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mahasiswa</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required>
            </div>
            <div class="mb-3">
                <label for="prodi" class="form-label">Program Studi</label>
                <select class="form-select" id="prodi" name="prodi" required>
                    <option selected disabled>Pilih Program Studi</option>
                    <option value="Informatika">Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Akuntansi">Akuntansi</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="angkatan" class="form-label">Angkatan</label>
                <select class="form-select" id="angkatan" name="angkatan" required>
                    <option selected disabled>Pilih Angkatan</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="sks" class="form-label">Jumlah SKS</label>
                <input type="number" class="form-control" id="sks" name="sks" placeholder="Masukkan Jumlah SKS" required>
            </div>

            <h2 class="mt-5 mb-4">Form Input Data Dosen</h2>
            <div class="mb-3">
                <label for="namaDosen" class="form-label">Nama Dosen</label>
                <input type="text" class="form-control" id="namaDosen" name="namaDosen" placeholder="Masukkan Nama Dosen" required>
            </div>
            <div class="mb-3">
                <label for="npdn" class="form-label">NPDN</label>
                <input type="text" class="form-control" id="npdn" name="npdn" placeholder="Masukkan NPDN" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

    <div class="container mt-5">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nim = $_POST['nim'];
            $namaMahasiswa = $_POST['nama'];
            $prodi = $_POST['prodi'];
            $angkatan = $_POST['angkatan'];
            $sks = $_POST['sks'];
            $namaDosen = $_POST['namaDosen'];
            $npdn = $_POST['npdn'];

            class Prodi
            {
                public $sks, $prodi, $angkatan, $biaya, $total;

                public function __construct($sks, $prodi, $angkatan)
                {
                    $this->sks = $sks;
                    $this->prodi = $prodi;
                    $this->angkatan = $angkatan;
                    $this->split();
                }

                public function split()
                {
                    if ($this->prodi == 'Informatika') {
                        if ($this->angkatan == '2024') {
                            $this->biaya = 180000;
                        } else if ($this->angkatan == '2023') {
                            $this->biaya = 175000;
                        } else if ($this->angkatan == '2022') {
                            $this->biaya = 170000;
                        }
                    } else if ($this->prodi == 'Sistem Informasi') {
                        if ($this->angkatan == '2024') {
                            $this->biaya = 195000;
                        } else if ($this->angkatan == '2023') {
                            $this->biaya = 185000;
                        } else if ($this->angkatan == '2022') {
                            $this->biaya = 190000;
                        }
                    } else if ($this->prodi == 'Akuntansi') {
                        if ($this->angkatan == '2024') {
                            $this->biaya = 210000;
                        } else if ($this->angkatan == '2023') {
                            $this->biaya = 215000;
                        } else if ($this->angkatan == '2022') {
                            $this->biaya = 220000;
                        }
                    }
                }

                public function bayar()
                {
                    if ($this->sks > 24) {
                        return "Mohon Maaf anda melebihi batas maksimal SKS";
                    } else {
                        $this->total = $this->biaya * $this->sks;
                        return $this->total;
                    }
                }

                public function output()
                {
                    return "
                    <h2>Total Biaya</h2>
                    <p>Total biaya SKS: Rp " . number_format($this->total, 0, ',', '.') . "</p>
                ";
                }
            }
            class Mahasiswa extends Prodi
            {
                public $nim, $nama;

                public function __construct($nim, $nama, $angkatan, $sks, $prodi)
                {
                    parent::__construct($sks, $prodi, $angkatan);
                    $this->nim = $nim;
                    $this->nama = $nama;
                }

                public function output()
                {
                    return "
                    <h2>Data Mahasiswa</h2>
                    <p>Nama Mahasiswa: " . $this->nama . "</p>
                    <p>NIM: " . $this->nim . "</p>
                    <p>Program Studi: " . $this->prodi . "</p>
                    <p>Angkatan: " . $this->angkatan . "</p>
                    <p>Total Biaya SKS: Rp " . number_format($this->total, 0, ',', '.') . "</p>
                ";
                }
            }
            class Dosen
            {
                public $namaDosen, $npdn, $gaji;

                public function __construct($namaDosen, $npdn, $totalMahasiswa)
                {
                    $this->namaDosen = $namaDosen;
                    $this->npdn = $npdn;
                    $this->gaji = $totalMahasiswa * 0.3;
                }
                public function output()
                {
                    return "
                    <h2>Data Dosen</h2>
                    <p>Nama Dosen: " . $this->namaDosen . "</p>
                    <p>NPDN: " . $this->npdn . "</p>
                    <p>Gaji Dosen: Rp " . number_format($this->gaji, 0, ',', '.') . "</p>
                ";
                }
            }

            $mahasiswa = new Mahasiswa($nim, $namaMahasiswa, $angkatan, $sks, $prodi);
            $mahasiswa->bayar();

            $dosen = new Dosen($namaDosen, $npdn, $mahasiswa->total);

            echo '<div class="card mb-3 p-3">';
            echo $mahasiswa->output();
            echo '</div>';

            echo '<div class="card mb-3 p-3">';
            echo $dosen->output();
            echo '</div>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>