<?php include 'koneksi.php';
session_start();
error_reporting(0);
// Memeriksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']);



// if (isset($_SESSION['username'])) {
// header("Location: RKLaptop.php");
// }



if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM user WHERE email='$email'";
  $result = mysqli_query($conn, $sql);
  if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['username'] = $row['username'];
    header("Location: RKLaptop.php");
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
  <!-- navbar -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Feather Icon -->
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="C:\xampp\htdocs\PROJECT-UTS-main\PROJECT UTS\fontawesome-free-6.3.0-web\css\all.css" />
  <script src="https://kit.fontawesome.com/9f3246d2c8.js" crossorigin="anonymous"></script>
  <!-- My Style -->
  <link rel="stylesheet" href="PROJECT UTS\css\style.css" />
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

  <!-- Section refund start -->
  <section class="refund-policy">
    <a href="warranty.php" style="padding-left: 2rem; text-decoration: underline">
      Bahasa Indonesia
      <a href="warranty english.php" style="padding-left: 1.5rem; text-decoration: underline">
        Bahasa Inggris</a></a>
    <li>Pastikan Anda membuat kesepakatan sebelumnya dengan Mitra kami untuk mengajukan Claim Garansi</li>
    <li>Anda bisa menghubungi Mitra Kami melalui WhatsApp pada saat Anda melakukan pengisian Form Janji</li>
    <li>Pastikan Claim Garansi tidak melebihi batas hari yang diberikan oleh Mitra Kami</li>
    <li>
      Claim garansi bisa didapatkan kecuali dengan kerusakan human error
    </li>
    <br />
    <h5 style="padding-left: 2rem">Kebijakan Claim Garansi</h5>
    <li>Pada Umumnya mitra kami memberikan batas waktu garansi selama satu bulan saja</li>
    <li>
      Jika melebihi tenggat waktu maka garansi tidak bisa di claim
    </li>
    <li>Harus membawa Struk dari tempat service mitra kami</li>

  </section>
  <!-- Section refund End -->
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
      <h5>RK Laptop</h5>
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
      <a href="https://www.instagram.com/rendypancaa">RK Laptop</a> 2023
      <br />
      All rights reserved.
    </p>
  </div>
  <script src="scriptLOGINREGISTER.js"></script>
  <!-- End of Page Wrapper -->

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
</body>

</html>