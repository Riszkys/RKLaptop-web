<?php
include 'koneksi.php';
session_start();

// Memeriksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']);

$host = 'localhost';
$username = 'root';
$password = "";
$database = 'fp_pemweb';
$koneksi = mysqli_connect($host, $username, $password, $database);

// if (isset($_SESSION['username'])) {
// header("Location: web konser.php");
// }
// Query untuk mengambil data
$query = "SELECT domisili, COUNT(nama_mitra) as jumlah_mitra FROM mitra GROUP BY domisili";
$result = mysqli_query($koneksi, $query);

// Menyiapkan data untuk grafik
$domisili = array();
$jumlahMitra = array();
while ($row = mysqli_fetch_assoc($result)) {
  $domisili[] = $row['domisili'];
  $jumlahMitra[] = $row['jumlah_mitra'];
}

// Query untuk mengambil data
$query = "SELECT alamat, COUNT(*) AS jumlah_customer FROM customer GROUP BY alamat";
$result = mysqli_query($koneksi, $query);

// Menyiapkan data untuk grafik
$labels = array();
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
  $labels[] = $row['alamat'];
  $data[] = $row['jumlah_customer'];
}


// Query untuk mengambil data merk_laptop dengan jumlah terbanyak
$query = "SELECT merk_laptop, COUNT(*) AS jumlah FROM form_janji GROUP BY merk_laptop ";
$result = mysqli_query($koneksi, $query);

// Menyiapkan data untuk tabel
$dataTable = array();
while ($row = mysqli_fetch_assoc($result)) {
  $merk_laptop = $row['merk_laptop'];
  $jumlah = $row['jumlah'];
  $dataTable[] = array($merk_laptop, $jumlah);
}
// Menutup koneksi database
mysqli_close($koneksi);


if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM user WHERE email='$email'";
  $result = mysqli_query($conn, $sql);
  if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['username'] = $row['username'];
    header("Location: web konser.php");
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


require_once("dompdf/autoload.inc.php");

use Dompdf\Dompdf;
// save pdf chart 1 
if (isset($_POST['savepdf1'])) {
  // Ambil data dari tabel mitra
  $sql = "SELECT id_mitra, nama_mitra, alamat_mitra, domisili FROM mitra";
  $result = $conn->query($sql);

  // Cek apakah ada data yang ditemukan
  if ($result->num_rows > 0) {
    // Inisialisasi library DOMPDF
    require_once 'dompdf/autoload.inc.php';

    $dompdf = new Dompdf();

    // Mulai membuat konten PDF
    $html = '<!DOCTYPE html>
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
            width:3%;
          }
          
          th:nth-child(2) {
            width: 30%;
          }
          th:nth-child(3) {
            width: 30%;
          }
          th:nth-child(4) {
            width: 30%;
          }
          h1,h3 { 
            text-align: center;
          }
          /* ... */
        </style>
      </head>
      <body>
        <h1>Data Mitra</h1>
        <table>
          <tr>
            <th>Id Mitra</th>
            <th>Nama Mitra</th>
            <th>Alamat Mitra</th>
            <th>Domisili Mitra</th>
            <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
          </tr>';

    // Loop melalui setiap baris data
    while ($row = $result->fetch_assoc()) {
      // Tambahkan data ke konten PDF
      $html .= '<tr>
            <td>' .  $row['id_mitra'] . '</td>
            <td>' . $row['nama_mitra'] . '</td>
            <td>' . $row['alamat_mitra'] . '</td>
            <td>' . $row['domisili'] . '</td>
            <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
          </tr>';
    }

    // Selesaikan konten HTML
    $html .= '</table>
        <h3>Itulah Daftar Mitra Kami...</h3>
      </body>
      </html>';

    // Membuat objek DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // Setting ukuran dan orientasi kertas
    $dompdf->setPaper('A4', 'portrait');

    // Render halaman HTML menjadi file PDF
    $dompdf->render();

    // Memberikan nama file PDF yang akan didownload
    $filename = 'PDF1.pdf';

    // Mengirimkan file PDF sebagai respon ke browser
    $dompdf->stream($filename, array('Attachment' => true));
  }
}
// EXCEL 1
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['saveexcel1'])) {
  // Ambil data dari tabel mitra
  $sql = "SELECT id_mitra, nama_mitra, alamat_mitra, domisili FROM mitra";
  $result = $conn->query($sql);

  // Cek apakah ada data yang ditemukan
  if ($result->num_rows > 0) {
    // Membuat objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menambahkan judul kolom
    $sheet->setCellValue('A1', 'Id Mitra');
    $sheet->setCellValue('B1', 'Nama Mitra');
    $sheet->setCellValue('C1', 'Alamat Mitra');
    $sheet->setCellValue('D1', 'Domisili Mitra');

    // Menambahkan data dari database
    $rowNumber = 2; // Baris pertama untuk judul kolom
    while ($row = $result->fetch_assoc()) {
      $sheet->setCellValue('A' . $rowNumber, $row['id_mitra']);
      $sheet->setCellValue('B' . $rowNumber, $row['nama_mitra']);
      $sheet->setCellValue('C' . $rowNumber, $row['alamat_mitra']);
      $sheet->setCellValue('D' . $rowNumber, $row['domisili']);

      $rowNumber++;
    }

    // Membuat objek Writer untuk menyimpan file Excel
    $writer = new Xlsx($spreadsheet);

    // Memberikan nama file Excel yang akan disimpan
    $filename = 'EXCEL1.xlsx';

    // Mengirimkan file Excel sebagai respon ke browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
  }
}

// save pdf 2
require_once 'dompdf/autoload.inc.php';
if (isset($_POST['savepdf2'])) {
  // Ambil data dari tabel customer
  $sql = "SELECT nama_customer, alamat, no_hp, email FROM customer";
  $result = $conn->query($sql);

  // Cek apakah ada data yang ditemukan
  if ($result->num_rows > 0) {
    // Inisialisasi library DOMPDF

    $dompdf = new Dompdf();

    // Mulai membuat konten PDF
    // Mulai membuat konten PDF
    $html = '<!DOCTYPE html>
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
         width:3%;
       }
       
       th:nth-child(2) {
         width: 30%;
       }
       th:nth-child(3) {
         width: 30%;
       }
       th:nth-child(4) {
         width: 30%;
       }
       h1,h3 { 
         text-align: center;
       }
       /* ... */
     </style>
   </head>
   <body>
     <h1>Data Customer</h1>
     <table>
       <tr>
         <th>Nama Customer/th>
         <th>Alamat Customer</th>
         <th>Nomor HP</th>
         <th>Email</th>
         <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
       </tr>';

    // Loop melalui setiap baris data
    while ($row = $result->fetch_assoc()) {
      // Tambahkan data ke konten PDF
      $html .= '<tr>
         <td>' .  $row['nama_customer'] . '</td>
         <td>' . $row['alamat'] . '</td>
         <td>' . $row['no_hp'] . '</td>
         <td>' . $row['email'] . '</td>
         <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
       </tr>';
    }

    // Selesaikan konten HTML
    $html .= '</table>
   </body>
   </html>';

    // Membuat objek DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // Setting ukuran dan orientasi kertas
    $dompdf->setPaper('A4', 'Portrait');

    // Render halaman HTML menjadi file PDF
    $dompdf->render();

    // Memberikan nama file PDF yang akan didownload
    $filename = 'data_customer.pdf';

    // Mengirimkan file PDF sebagai respon ke browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $dompdf->stream($filename);
    exit;
  }
}

// save excel 2

if (isset($_POST['saveexcel2'])) {
  // Ambil data dari tabel customer
  $sql = "SELECT nama_customer, alamat, no_hp, email FROM customer";
  $result = $conn->query($sql);

  // Cek apakah ada data yang ditemukan
  if ($result->num_rows > 0) {
    // Menginisialisasi objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menambahkan judul kolom
    $sheet->setCellValue('A1', 'Nama Customer');
    $sheet->setCellValue('B1', 'Alamat');
    $sheet->setCellValue('C1', 'No. HP');
    $sheet->setCellValue('D1', 'Email');

    // Loop melalui setiap baris data
    $rowIndex = 2;
    while ($row = $result->fetch_assoc()) {
      // Menambahkan data ke dalam kolom
      $sheet->setCellValue('A' . $rowIndex, $row['nama_customer']);
      $sheet->setCellValue('B' . $rowIndex, $row['alamat']);
      $sheet->setCellValue('C' . $rowIndex, $row['no_hp']);
      $sheet->setCellValue('D' . $rowIndex, $row['email']);
      $rowIndex++;
    }

    // Mengatur lebar kolom secara otomatis
    foreach (range('A', 'D') as $column) {
      $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Membuat objek Writer dan menyimpan ke file Excel
    $writer = new Xlsx($spreadsheet);
    $filename = 'data_customer.xlsx';
    $writer->save($filename);

    // Mengirimkan file Excel sebagai respon ke browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    readfile($filename);
    exit;
  }
}

// save pdf 3
require_once 'dompdf/autoload.inc.php';

if (isset($_POST['savepdf3'])) {
  // Ambil data dari tabel form_janji
  $sql = "SELECT nama, alamat, email, merk_laptop, layanan, keluhan FROM form_janji";
  $result = $conn->query($sql);

  // Cek apakah ada data yang ditemukan
  if ($result->num_rows > 0) {
    // Inisialisasi library DOMPDF
    $dompdf = new Dompdf();

    // Mulai membuat konten PDF
    $html = '<!DOCTYPE html>
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
         width: 20%;
       }
       
       th:nth-child(2) {
         width: 20%;
       }
       th:nth-child(3) {
         width: 20%;
       }
       th:nth-child(4) {
         width: 20%;
       }
       th:nth-child(5) {
         width: 20%;
       }
       th:nth-child(6) {
         width: 20%;
       }
       h1,h3 { 
         text-align: center;
       }
       /* ... */
     </style>
   </head>
   <body>
     <h1>Data Service Customer</h1>
     <table>
       <tr>
         <th>Nama</th>
         <th>Alamat</th>
         <th>Email</th>
         <th>Merk Laptop</th>
         <th>Layanan</th>
         <th>Keluhan</th>
       </tr>';

    // Loop melalui setiap baris data
    while ($row = $result->fetch_assoc()) {
      // Tambahkan data ke konten PDF
      $html .= '<tr>
         <td>' .  $row['nama'] . '</td>
         <td>' . $row['alamat'] . '</td>
         <td>' . $row['email'] . '</td>
         <td>' . $row['merk_laptop'] . '</td>
         <td>' . $row['layanan'] . '</td>
         <td>' . $row['keluhan'] . '</td>
       </tr>';
    }

    // Selesaikan konten HTML
    $html .= '</table>
   </body>
   </html>';

    // Membuat objek DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // Setting ukuran dan orientasi kertas
    $dompdf->setPaper('A4', 'Portrait');

    // Render halaman HTML menjadi file PDF
    $dompdf->render();

    // Memberikan nama file PDF yang akan didownload
    $filename = 'data_layanan_customer.pdf';

    // Mengirimkan file PDF sebagai respon ke browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $dompdf->stream($filename);
    exit;
  }
}
// save pdf 3
require_once 'vendor/autoload.php';

if (isset($_POST['saveexcel3'])) {
  // Ambil data dari tabel form_janji
  $sql = "SELECT nama, alamat, email, merk_laptop, layanan, keluhan FROM form_janji";
  $result = $conn->query($sql);

  // Cek apakah ada data yang ditemukan
  if ($result->num_rows > 0) {
    // Inisialisasi objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set judul kolom
    $sheet->setCellValue('A1', 'Nama');
    $sheet->setCellValue('B1', 'Alamat');
    $sheet->setCellValue('C1', 'Email');
    $sheet->setCellValue('D1', 'Merk Laptop');
    $sheet->setCellValue('E1', 'Layanan');
    $sheet->setCellValue('F1', 'Keluhan');

    // Loop melalui setiap baris data
    $rowNumber = 2;
    while ($row = $result->fetch_assoc()) {
      // Tambahkan data ke spreadsheet
      $sheet->setCellValue('A' . $rowNumber, $row['nama']);
      $sheet->setCellValue('B' . $rowNumber, $row['alamat']);
      $sheet->setCellValue('C' . $rowNumber, $row['email']);
      $sheet->setCellValue('D' . $rowNumber, $row['merk_laptop']);
      $sheet->setCellValue('E' . $rowNumber, $row['layanan']);
      $sheet->setCellValue('F' . $rowNumber, $row['keluhan']);

      $rowNumber++;
    }

    // Mengatur lebar kolom agar sesuai
    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('B')->setWidth(30);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(50);

    // Mengatur judul sheet
    $sheet->setTitle('Data Form Janji');

    // Membuat objek Writer untuk menyimpan Spreadsheet dalam format XLSX
    $writer = new Xlsx($spreadsheet);

    // Memberikan nama file Excel yang akan didownload
    $filename = 'data_service_cust.xlsx';

    // Mengirimkan file Excel sebagai respon ke browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
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
  <link rel="stylesheet" href="DesignWebRKLaptop.css" />
  <!-- Feather Icon -->
  <script src="https://unpkg.com/feather-icons"></script>
  <!-- My Style -->
  <link rel="stylesheet" href="PROJECT UTS\css\style.css" />
  <link rel="stylesheet" href="C:\xampp\htdocs\PROJECT-UTS-main\PROJECT UTS\fontawesome-free-6.3.0-web\css\all.css" />
  <script src="https://kit.fontawesome.com/9f3246d2c8.js" crossorigin="anonymous"></script>
  <!-- navbar -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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
            <input class="form-control" type="text" placeholder="Cari toko" style="width: 250px" id="search-item" onkeyup="search()" />
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
        <a class="navbar-brand" href="RKLaptop.php.php">
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

  <!-- Start tentang kami -->
  <section class="about-us" style="background-color: #fff; display: flex; justify-content: center; align-items: center; flex-direction: column;">
    <h2 style="color: #2d2b2b;">Tentang <span>Kami</span></h2>
    <hr size="1px" width="100%" align="left" color="white" />
    <div style="display: flex;" class="mitra-kami" style="width: 400px; color: #2d2b2b;">
      <div style="width: 450px; color: #2d2b2b;">
        <h3 style="text-align: center; font-size: small">Daftar Mitra Kami</h3>
        <canvas id="myChart"></canvas>
        <form action="" method="POST">
          <div class="buttonsave" style="display: flex; justify-content: center;">
            <button name="savepdf1" class="btnLogin-popup btn-primary">Save as PDF</button>
            <div>
              <button name="saveexcel1" class="btnLogin-popup btn-primary">Save as Excel</button>
            </div>
          </div>
        </form>
      </div>
      <div style="width: 450px; color: #2d2b2b;">
        <h3 style="text-align: center; font-size: small;">Jumlah Customer Kami di Berbagai Daerah</h3>
        <canvas id="myChart2"></canvas>
        <form action="" method="POST">
          <div class="buttonsave" style="display: flex; justify-content: center;">
            <button name="savepdf2" class="btnLogin-popup btn-primary">Save as PDF</button>
            <div>
              <button name="saveexcel2" class="btnLogin-popup btn-primary">Save as Excel</button>
            </div>
          </div>
        </form>
      </div>
      <div style="width: 450px; color: #2d2b2b;">
        <h3 style="text-align: center; font-size: small;">Merk Laptop yang Sering Rusak</h3>
        <canvas id="myChart3"></canvas>
        <form action="" method="POST">
          <div class="buttonsave" style="display: flex; justify-content: center;">
            <button name="savepdf3" class="btnLogin-popup btn-primary">Save as PDF</button>
            <div>
              <button name="saveexcel3" class="btnLogin-popup btn-primary">Save as Excel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- End -->
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

  <!-- Social buttons -->
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
  <script src="scriptLOGINREGISTER.js"></script>
  <!-- End of Page Wrapper -->
  <!-- highchart -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <!-- Custom scripts -->
  <script src="js/creative.min.js"></script>
  <script>
    // Menangkap klik tombol Logout
    document.getElementById("logoutBtn").addEventListener("click", function() {
      // Mengubah tombol menjadi tombol Login
      this.innerHTML =
        '<button type="button" class="btnLogin-popup btn-primary" data-toggle="modal" data-target="#exampleModalLong">🔒 | Login </button>';
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    var domisili = <?php echo json_encode($domisili); ?>;
    var jumlahMitra = <?php echo json_encode($jumlahMitra); ?>;

    // Membuat grafik menggunakan Chart.js
    var ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: domisili,
        datasets: [{
          label: 'Jumlah Mitra',
          data: jumlahMitra,
          backgroundColor: 'rgba(0, 0, 0, 0.2)',
          borderColor: 'rgba(255, 215, 0, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Jumlah Mitra'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Domisili'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              color: 'black'
            }
          },
          tooltip: {
            bodyColor: 'black',
            titleColor: 'black'
          }
        }
      }
    });
  </script>
  <script>
    var labels = <?php echo json_encode($labels); ?>;
    var data = <?php echo json_encode($data); ?>;

    // Membuat grafik menggunakan Chart.js
    var ctx = document.getElementById('myChart2').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Jumlah Customer',
          data: data,
          backgroundColor: 'rgba(0, 0, 0, 0.2)',
          borderColor: 'rgba(255, 215, 0, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Jumlah Customer'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Alamat'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              color: 'black'
            }
          },
          tooltip: {
            bodyColor: 'black',
            titleColor: 'black'
          }
        }
      }
    });
  </script>
  <script>
    var labels = <?php echo json_encode(array_column($dataTable, 0)); ?>;
    var data = <?php echo json_encode(array_column($dataTable, 1)); ?>;

    // Membuat tabel menggunakan Chart.js
    var ctx = document.getElementById('myChart3').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Jumlah',
          data: data,
          backgroundColor: 'rgba(0, 0, 0, 0.2)',
          borderColor: 'rgba(255, 215, 0, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Jumlah'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Merk Laptop'
            }
          }
        },
        plugins: {
          legend: {
            labels: {
              color: 'black'
            }
          },
          tooltip: {
            bodyColor: 'black',
            titleColor: 'black'
          }
        }
      }
    });
  </script>
</body>

</html>