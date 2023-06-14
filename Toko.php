<?php
include 'koneksi.php';
session_start();
error_reporting(0);
// Memeriksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']);

// if (isset($_SESSION['username'])) {
//   header("Location: web konser.php");
// }


if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM user WHERE email='$email'";
  $result = mysqli_query($conn, $sql);
  if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['username'] = $row['username'];
    // header("Location: web konser.php");
  } else {
    echo "<script>
  alert('Woops! Email Atau Password anda Salah.')
</script>";
  }
}


if (isset($_POST['submit1'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "INSERT INTO user (username, email, password)
VALUES ('$username', '$email', '$password')";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    echo "<script>
  alert('Wow! User Registration Completed.')
</script>";
    $username = "";
    $email = "";
    $_POST['password'] = "";
  }
}

if (isset($_POST['btnhelp'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $nohp = $_POST['nohp'];
  $pesan = $_POST['pesan'];

  $sql = "INSERT INTO help_cust (nama_customer, email, no_hp, pesan)
VALUES ('$nama', '$email', '$nohp', '$pesan')";
  $result = mysqli_query($conn, $sql);
  if ($result) {
    echo "<script>
  alert('Bantuan Anda Telah Dikirim!')
</script>";
    $username = "";
    $email = "";
    $_POST['password'] = "";
  }
}
// save form janjian
if (isset($_POST['btnjanjian'])) {
  $nomorantrian = $_POST['antrian'];
  $namacust = $_POST['nama'];
  $alamatcust = $_POST['alamat'];
  $emailcust = $_POST['email'];
  $nohpcust = $_POST['nohp'];
  $tanggalketemu = $_POST['tanggalketemu'];
  $jamketemu = $_POST['jamketemu'];
  $jenislaptop = $_POST['laptop'];
  $jenislayanan = implode(', ', $_POST['layanan']);
  $keluhan = $_POST['keluhan'];


  $sql = "INSERT INTO form_janji (nomor_antrian, nama, alamat, email, no_hp, tanggal, jam, merk_laptop, layanan ,keluhan)
    VALUES ('$nomorantrian', '$namacust', '$alamatcust', '$emailcust', '$nohpcust', '$tanggalketemu', '$jamketemu', '$jenislaptop', '$jenislayanan', '$keluhan')";

  $result = mysqli_query($conn, $sql);
  if ($result) {
    echo "<script>
            alert('Form Janjian Anda Telah Dikirim!');
          </script>";
  }
}

// end save form janjian

function generateQueueNumber($conn)
{
  // Mendapatkan nomor antrian terbaru dari database
  $query = "SELECT nomor_antrian FROM form_janji ORDER BY nomor_antrian DESC LIMIT 1";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastNumber = $row['nomor_antrian'];

    // Menambahkan nomor antrian baru
    $newNumber = $lastNumber + 1;
  } else {
    // Jika tidak ada nomor antrian sebelumnya, mulai dari 1
    $newNumber = 1;
  }

  // Format nomor antrian menjadi 3 digit dengan leading zero jika diperlukan
  $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

  // Memasukkan nomor antrian baru ke database
  // $insertQuery = "INSERT INTO form_janji (nomor_antrian, created_at) VALUES ('$formattedNumber', NOW())";
  // $conn->query($insertQuery);

  return $formattedNumber;
}

// Menggunakan koneksi ke database yang sudah ada sebelumnya

// Panggil fungsi untuk menghasilkan nomor antrian baru
$newNumber = generateQueueNumber($conn);

// Format nomor antrian menjadi 3 digit dengan leading zero jika diperlukan
$formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

// Tampilkan nomor antrian
// echo $formattedNumber;

// save pdf
require_once("dompdf/autoload.inc.php");

use Dompdf\Dompdf;

if (isset($_POST['btnsavepdf'])) {
  // Mendapatkan data dari form atau database
  $nomorantrian = $_POST['antrian'];
  $namacust = $_POST['nama'];
  $alamatcust = $_POST['alamat'];
  $emailcust = $_POST['email'];
  $nohpcust = $_POST['nohp'];
  $tanggalketemu = $_POST['tanggalketemu'];
  $jamketemu = $_POST['jamketemu'];
  $jenislaptop = $_POST['laptop'];
  $jenislayanan = implode(', ', $_POST['layanan']);
  $keluhan = $_POST['keluhan'];
  // Membuat konten HTML untuk file PDF
  $html = '
<!DOCTYPE html>
<html>
<head>
  <style>
    /* CSS styling untuk tampilan PDF */
    table {
      width: 100%;
    }
    
    th, td {
      padding: 5px;
      text-align: center;
    }
    /* Atur panjang kolom sesuai kebutuhan */
    th:nth-child(1) {
      width: 8%;
    }
    
    th:nth-child(2) {
      width: 30%;
    }
    th:nth-child(3) {
      width: 30%;
    }
    th:nth-child(3) {
      width: 30%;
    }
    th:nth-child(4) {
      width: 30%;
    }
    th:nth-child(5) {
      width: 30%;
    }
    th:nth-child(6) {
      width: 30%;
    }
    th:nth-child(7) {
      width: 30%;
    }
    th:nth-child(8) {
      width: 30%;
    }
    th:nth-child(9) {
      width: 30%;
    }
    th:nth-child(10) {
      width: 30%;
    }
    h1 { 
    text-align: center;
    }
    /* ... */
  </style>
</head>
<body>
  <h1>Bukti Form Janjian</h1>
  <table>
    <tr>
      <th>Nomor Antrian</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>Email</th>
      <th>No. HP</th>
      <th>Tanggal Ketemuan</th>
      <th>Jam Ketemuan</th>
      <th>Jenis Laptop</th>
      <th>Layanan</th>
      <th>Keluhan</th>
      <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
    </tr>
    <tr>
      <td>' . $nomorantrian . '</td>
      <td>' . $namacust . '</td>
      <td>' . $alamatcust . '</td>
      <td>' . $emailcust . '</td>
      <td>' . $nohpcust . '</td>
      <td>' . $tanggalketemu . '</td>
      <td>' . $jamketemu . '</td>
      <td>' . $jenislaptop . '</td>
      <td>' . $jenislayanan . '</td>
      <td>' . $keluhan . '</td>
      <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
    </tr>
  </table>
  <!-- Tambahkan informasi lainnya ke dalam HTML -->
  <h1>Terimakasih Telah Membuat Form Janjian...</h1>
</body>
</html>';

  // Membuat objek DOMPDF
  $dompdf = new Dompdf();
  $dompdf->loadHtml($html);

  // Setting ukuran dan orientasi kertas
  $dompdf->setPaper('A4', 'landscape');

  // Render halaman HTML menjadi file PDF
  $dompdf->render();

  // Memberikan nama file PDF yang akan didownload
  $filename = 'form_janji.pdf';

  // Mengirimkan file PDF sebagai respon ke browser
  $dompdf->stream($filename, array('Attachment' => true));
}

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['btnsaveexcel'])) {
  $nomorantrian = $_POST['antrian'];
  $namacust = $_POST['nama'];
  $alamatcust = $_POST['alamat'];
  $emailcust = $_POST['email'];
  $nohpcust = $_POST['nohp'];
  $tanggalketemu = $_POST['tanggalketemu'];
  $jamketemu = $_POST['jamketemu'];
  $jenislaptop = $_POST['laptop'];
  $jenislayanan = implode(', ', $_POST['layanan']);
  $keluhan = $_POST['keluhan'];

  try {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'NOMOR ANTRIAN');
    $sheet->setCellValue('B1', 'NAMA LENGKAP');
    $sheet->setCellValue('C1', 'ALAMAT');
    $sheet->setCellValue('D1', 'EMAIL');
    $sheet->setCellValue('E1', 'NO HP');
    $sheet->setCellValue('F1', 'TANGGAL JANJIAN');
    $sheet->setCellValue('G1', 'JAM JANJIAN');
    $sheet->setCellValue('H1', 'MERK LAPTOP');
    $sheet->setCellValue('I1', 'JENIS LAYANAN');
    $sheet->setCellValue('J1', 'KELUHAN');

    // Set the row number
    $rowNumber = 2;

    $sheet->setCellValue('A' . $rowNumber, $nomorantrian);
    $sheet->setCellValue('B' . $rowNumber, $namacust);
    $sheet->setCellValue('C' . $rowNumber, $alamatcust);
    $sheet->setCellValue('D' . $rowNumber, $emailcust);
    $sheet->setCellValue('E' . $rowNumber, $nohpcust);
    $sheet->setCellValue('F' . $rowNumber, $tanggalketemu);
    $sheet->setCellValue('G' . $rowNumber, $jamketemu);
    $sheet->setCellValue('H' . $rowNumber, $jenislaptop);
    $sheet->setCellValue('I' . $rowNumber, $jenislayanan);
    $sheet->setCellValue('J' . $rowNumber, $keluhan);

    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ];

    // Save the Excel file
    $writer = new Xlsx($spreadsheet);
    $writer->save('Bukti Janjian.xlsx');

    echo "File Excel berhasil disimpan.";
  } catch (Exception $e) {
    echo "Terjadi kesalahan saat menyimpan file Excel: " . $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RK Laptop - Booking Sekarang!</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <!-- navbar -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="PROJECT UTS\bootstrap-4.5.3-dist\css\bootstrap.css" />
  <!-- Slick CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

  <link rel="stylesheet" href="DesignWebRKLaptop.css" />
  <!-- Feather Icon -->
  <script src="https://unpkg.com/feather-icons"></script>
  <!-- fontaawesome -->
  <link rel="stylesheet" href="C:\xampp\htdocs\PROJECT-UTS-main\PROJECT UTS\fontawesome-free-6.3.0-web\css\all.css" />
  <script src="https://kit.fontawesome.com/9f3246d2c8.js" crossorigin="anonymous"></script>
</head>

<body>
  <?php if ($isLoggedIn) : ?>
    <!-- Tampilan jika pengguna sudah login -->
    <!-- <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p> -->
    <header class="header sticky-top" style="
        padding: 0px 0;
        background-color: #2d2b2b;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
      ">
      <nav class="navbar navbar-expand-lg navbar-light navbar-sm">
        <a class="navbar-brand" href="RKLaptop.php">
          <img src="img/Logo RK Laptop Paling Bagus.png" alt="RK Laptop" style="display: auto; width: 120px; border-radius: 50%" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="RKLaptop.php" style="color: white">BERANDA <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Toko.php" style="color: white">TOKO</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="tentang kami.php" style="color: white">TENTANG KAMI</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php" style="color: white">KONTAK KAMI</a>
            </li>
          </ul>
          <form class="form-inline" style="padding-bottom: 1rem">
            <input class="form-control" type="text" placeholder="Cari Toko" style="width: 250px" id="search-item" onkeyup="search()" />
            <input type="submit" id="mybtn" value="cari" class="btn btn-outline-secondary" onclick="search()" />
          </form>
          <!-- <button type="button" class="btnLogin-popup btn-primary" data-toggle="modal" data-target="#exampleModalLong">
            🔒 | Login
          </button> -->
          <button type="button" id="logoutBtn" class="btnLogin-popup btn-primary" name="logout" data-toggle="modal" method="POST">
            Log Out
          </button>
          <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <!-- Isi modal login -->
            <button type="button" class="btnLogin-popup btn-primary" data-toggle="modal" data-target="#exampleModalLong">
              🔒 | Login
            </button>
          </div>
        </div>
      </nav>
    </header>

    <!-- Konten lainnya -->
  <?php else : ?>
    <!-- Tampilan jika pengguna belum login -->
    <header class="header sticky-top" style="
        padding: 0px 0;
        background-color: #2d2b2b;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
      ">
      <nav class="navbar navbar-expand-lg navbar-light navbar-sm">
        <a class="navbar-brand" href="RKLaptop.php">
          <img src="img/Logo RK Laptop Paling Bagus.png" alt="RK Laptop" style="display: auto; width: 120px; border-radius: 50%" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="RKLaptop.php" style="color: white">BERANDA <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Toko.php" style="color: white">TOKO</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="tentang kami.php" style="color: white">TENTANG KAMI</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php" style="color: white">KONTAK KAMI</a>
            </li>
          </ul>
          <form class="form-inline" style="padding-bottom: 1rem">
            <input class="form-control" type="text" placeholder="Cari toko" style="width: 250px" id="search-item" onkeyup="search()" />
            <input type="submit" id="mybtn" value="cari" class="btn btn-outline-secondary" onclick="search()" />
          </form>
          <button type="button" class="btnLogin-popup btn-primary" data-toggle="modal" data-target="#exampleModalLong">
            🔒 | Login
          </button>
        </div>
      </nav>
    </header>
    <!-- Konten lainnya -->
  <?php endif; ?>


  <!-- LOGINREGISTER Modal - START -->
  <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="wrapper modal-content">
        <div class="modal-header">
          <button class="icon-close" data-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-xmark" style="color: black"></i>
          </button>
        </div>
        <div class="form-box login">
          <h2 style="color: whitesmoke">Login</h2>
          <form action="#">
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-envelope" style="color: white"></i></span>
              <input type="email" required style="color: white" />
              <label style="color: white">Email</label>
            </div>
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-lock" style="color: white"></i></span>
              <input type="password" required style="color: white" />
              <label style="color: white">Password</label>
            </div>
            <div class="remember-forgot">
              <label style="color: white"><input type="checkbox" style="color: white" />Remember
                Me</label>
              <a href="#" style="color: white">Forgot Password?</a>
            </div>
            <button type="submit" class="btnLGN" id="btnLogin" style="background-color: white; color: black">
              Login
            </button>
            <div class="login-register">
              <p style="color: white">
                Don't have an account?
                <a href="#" class="register-link" style="color: white">Register</a>
              </p>
            </div>
          </form>
        </div>
        <div class="form-box register">
          <h2 style="color: whitesmoke">Registration</h2>
          <form action="#">
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-user" style="color: white"></i></span>
              <input type="text" required style="color: white" />
              <label style="color: white">Username</label>
            </div>
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-envelope" style="color: white"></i></span>
              <input type="email" required style="color: white" />
              <label style="color: white">Email</label>
            </div>
            <div class="input-box">
              <span class="icon"><i class="fa-solid fa-lock" style="color: white"></i></span>
              <input type="password" required style="color: white" />
              <label style="color: white">Password</label>
            </div>
            <div class="remember-forgot">
              <label style="color: white"><input type="checkbox" style="color: white" />I agree to the
                terms &amp; conditions</label>
            </div>
            <button type="submit" class="btnLGN" id="btnRegister" style="background-color: white; color: black">
              Register
            </button>
            <div class="login-register">
              <p style="color: white">
                Already have an account?
                <a href="#" class="login-link" style="color: white">Login</a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- LOGINREGISTER Modal - END -->
  <!-- Fitur Search  -->

  <!-- modal -->
  <button type="button" class="btn btn-primary d-none btnmodal" data-toggle="modal" data-target="#exampleModal">
    launch demo modal
  </button>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title modaltittle" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body row">
          <div class="modalimage col-md-6 col-12"></div>
          <div class="col-md-6 col-12">
            <div class="modaldeskripsi"></div>
            <div class="card-body">
              <h5 class="card-title">Konser Rock</h5>
              <p class="card-text">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed
                pellentesque purus at bibendum rhoncus. In nec felis blandit,
                efficitur neque vel, sodales arcu.
              </p>
              <a href="" target="_blank" class="btn-warning btnbeli">Beli Tiket</a>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal -->

  <!-- Konser Populer -->

  <div id="konser">
    <section id="popular-concerts">
      <div class="container my-4">
        <h2 class="my-4" style="color: black">Daftar Store Service Laptop Recommended Area Surabaya 2023</h2>
        <!-- <form method="POST">
          <label for="sort">Sort By:</label>
          <select id="sort" name="sort">
            <option value="Surabaya Timur">Surabaya Timur</option>
            <option value="Surabaya Barat">Surabaya Barat</option>
            <option value="Surabaya Pusat">Surabaya Pusat</option>
            <option value="Surabaya Utara">Surabaya Utara</option>
            <option value="Surabaya Selatan">Surabaya Selatan</option>
          </select>
          <button type="submit" name="carikota">Sort</button>
        </form> -->
        <!-- end fitur search -->
        <div class="row">
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=1" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '1'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=2" class="card-img-top" alt="..." />
              <div class="d-none deskripsi">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Dolore et placeat provident quis, nihil atque aperiam debitis
                hic reprehenderit consectetur molestiae rem aliquam magni
                perspiciatis, amet deleniti veritatis. Dicta nisi at beatae
                aspernatur officia ullam, ipsa voluptate vel obcaecati
                aperiam?
              </div>
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '2'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=3" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '3'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=4" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '4'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=5" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '5'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=6" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '6'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=6" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '7'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=6" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '8'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card">
              <img src="https://picsum.photos/300/200?random=6" class="card-img-top" alt="..." />
              <div class="card-body">
                <?php
                // Mengambil nilai mitra dari database
                $query = "SELECT nama_mitra, alamat_mitra, domisili FROM mitra WHERE id_mitra = '9'"; // Ganti 'table_mitra' dengan nama tabel yang sesuai
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $namaMitra = $row['nama_mitra'];
                $alamatMitra = $row['alamat_mitra'];
                $domisili = $row['domisili'];
                ?>
                <h5 class="card-title" id="namamitra"> <?php echo $namaMitra; ?></h5>
                <p class="card-text">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo $alamatMitra . "<br> <i class='fa-solid fa-landmark-dome'></i> " . $domisili; ?>
                </p>
                <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="setSelectedToko()">Buat Janji Temu</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- END  -->

  <!-- Modal form -->
  <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalTitle">Pengisian Form Janji Temu Service Laptop</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Form pengisian tiket -->
          <form method="POST" action="" class="form-inputan">
            <!-- Isi form sesuai kebutuhan -->
            <div class="form form-janji">
              <label for="Nomor Antrian" style="width: 5rem;">Nomor Antrian </label>
              <input type="number" class="form-input" id="nama" name="antrian" style="width: 80%;" value="<?php echo $formattedNumber; ?>" readonly>
            </div>
            <div class="form form-janji">
              <label for="nama" style="width: 5rem;">Nama </label>
              <input type="text" class="form-input" id="nama" name="nama" required style="width: 80%;">
            </div>
            <div class="form-group">
              <label for="alamat" style="width: 5rem;">Alamat </label>
              <input type="text" class="form-input" id="alamat" name="alamat" required style="width: 80%;">
            </div>
            <div class="form-group">
              <label for="email" style="width: 5rem;">Email </label>
              <input type="email" class="form-input" id="Email" name="email" required style="width: 80%;">
            </div>
            <div class="form-group">
              <label for="no hp" style="width: 5rem;">No HP </label>
              <input type="number" class="form-input" id="nohp" name="nohp" required style="width: 80%;">
            </div>
            <div class="form-group" style="display: flex;">
              <label for="Waktu Janjian" style="width: 5.2rem;">Waktu Janjian </label>
              <input type="date" class="form-input" id="tanggalketemu" name="tanggalketemu" required style="width: 40%;">
              <input type="time" class="form-input" id="waktu" name="jamketemu" required style="width: 40%;">
            </div>
            <div class="form-group">
              <label for="Merk Laptop" style="width: 5rem;">Merk Laptop </label>
              <input type="text" class="form-input" id="email" name="laptop" required style="width: 80%;">
            </div>
            <div class="form-group">
              <label for="email" style="width: 5.3rem;">Layanan yg Dibutuhkan </label>
              <input type="checkbox" id="option1" name="layanan[]" value="Upgrade Ram"> Upgrade Ram
              <input type="checkbox" id="option1" name="layanan[]" value="Upgrade SSD"> Upgrade SSD
              <input type="checkbox" id="option1" name="layanan[]" value="Ganti LCD"> Ganti LCD
              <br>
              <div style="padding-left:5.5rem ;">
                <input type="checkbox" id="option1" name="layanan[]" value="Ganti Keyboard"> Ganti Keyboard
                <input type="checkbox" id="option1" name="layanan[]" value="Ganti Thermal Paste"> Ganti Thermal Paste
                <br>
                <input type="checkbox" id="option1" name="layanan[]" value="Service Kipas Laptop"> Service Kipas Laptop
                <input type="checkbox" id="option1" name="layanan[]" value="Servie Baterai"> Servie Baterai
                <input type="checkbox" id="option1" name="layanan[]" value="Upgrade Windows"> Upgrade Windows
                <input type="checkbox" id="option1" name="layanan[]" value="Servie Speaker">Servie Speaker
                <br>
                <input type="checkbox" id="option1" name="layanan[]" value="Lainnya"> Lainnya
              </div>

            </div>
            <div class="form-group">
              <label for="keluhan" style="width: 5rem;">Keluhan</label>
              <input type="text" class="form-input" id="keluhan" name="keluhan" required style="width: 80%;">
            </div>
            <!-- <div class="form-group">
              <label for="Nama Tempat Service" style="width: 5rem;">Nama Tempat Service</label>
              <input type="text" id="toko" name="toko" style="width: 80%;" readonly>
            </div> -->
            <div style="justify-content: center;">
              <button type="submit" class="btn btn-primary" name="btnjanjian">Submit</button>
              <a href="#" class="btn-KP" data-toggle="modal" data-target="#formModal" onclick="openWhatsAppChat()">Chat via WA</a>
            </div>
            <div style="padding-top: 1rem; justify-content: center;">
              <button type="submit" class="btn btn-primary" name="btnsaveexcel">Simpan Excel</button>
              <!-- <a href="generate_pdf.php" class="btn-KP" target="_blank">Download PDF</a> -->
              <button type="submit" class="btn btn-primary" name="btnsavepdf">Simpan Bukti</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Form modal -->

  <!-- Call to action -->
  <!-- BAR BAWAH -->
  <section class="navbar-bottom">
    <div class="row-navbar-bottom">
      <h6 style="cursor: pointer">
        <a href="warranty.php" style="color: black"> Warranty Claims Policy</a>
      </h6>
      <hr size="1px" width="250px" align="left" color="black" />
      <h6 style="cursor: pointer">
        <a href="how to get a ticket.php" style="color: black">How To Fill Out The Sppointment Form?</a>
      </h6>
      <hr size="1px" width="250px" align="left" color="black" />
      <h6 style="cursor: pointer">
        <a href="faq.php" style="color: black">FAQ</a>
      </h6>
      <hr size="1px" width="250px" align="left" color="black" />
    </div>
    <div class="content-navbar-bottom">
      <h5>Tech Ops</h5>
      <h5>Find us On</h5>
      <br />
      <div class="icon-navbar-bottom">
        <a href="#"><i class="fa-brands fa-twitter" style="color: whitesmoke"></i>
        </a>
        <a href="#"><i class="fa-brands fa-instagram" style="color: whitesmoke"></i>
        </a>
        <a href="#"><i class="fa-brands fa-whatsapp" style="color: whitesmoke"></i>
        </a>
        <a href="#"><i class="fa-brands fa-facebook" style="color: whitesmoke"></i>
        </a>
      </div>
    </div>
  </section>
  <!-- End Bar Bawah -->
  <hr />
  <!-- Footer Links -->
  <div class="container-fluid text-center">
    <p>
      Copyright © by
      <a href="https://www.instagram.com/rendypancaa">Tech Ops</a> 2023
      <br />
      All rights reserved.
    </p>
  </div>

  <!-- End of Page Wrapper -->
  <!-- Bootstrap core JavaScript -->
  <script>
    const togglebtn = document.querySelector(".toggle_btn");
    const togglebtnicon = document.querySelector(".toggle_btn i");
    const dropdownmenu = document.querySelector(".dropdown_menu");

    togglebtn.onclick = function() {
      dropdownmenu.classList.toggle("open");
      const isopen = dropdownmenu.classList.contains("open");

      togglebtnicon.classList = isopen ?
        "fa-solid fa-xmark" :
        "fa-solid fa-bars";
    };

    function openWhatsAppChat() {
      // Nomor telepon atau ID WhatsApp yang ingin dituju
      var phoneNumber = "6285158911396";

      // Membangun URL untuk mengarahkan ke aplikasi WhatsApp
      var url = "https://api.whatsapp.com/send?phone=" + phoneNumber;

      // Membuka jendela baru atau tab dengan URL WhatsApp
      window.open(url);
    }
  </script>

  <!-- Custom scripts -->
  <script src="js/creative.min.js"></script>
  <!-- Our Script -->
  <script>
    $(document).ready(function() {
      console.log("Im ready");
    });
  </script>

  <!-- JQuery -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- Slick JS -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <!-- Script Form Login dan Register -->
  <script src="scriptLOGINREGISTER.js"></script>
  <script src="js\popper.min.js"></script>
  <script src="js\bootstrap.min.js"></script>

  <!-- Our Script -->
  <script>
    $(document).ready(function() {
      console.log("Im ready");
    });
    // Menangkap klik tombol Logout
    document.getElementById("logoutBtn").addEventListener("click", function() {
      // Mengubah tombol menjadi tombol Login
      this.innerHTML =
        '<button type="button" class="btnLogin-popup btn-primary" data-toggle="modal" data-target="#exampleModalLong">🔒 | Login </button>';
    });
  </script>
  <script>
    // Menangkap klik tombol Logout
    document.getElementById("logoutBtn").addEventListener("click", function() {
      // Mengubah tombol menjadi tombol Login
      this.innerHTML =
        '<button type="button" class="btnLogin-popup btn-primary" data-toggle="modal" data-target="#exampleModalLong">🔒 | Login </button>';
    });
  </script>
</body>

</html>