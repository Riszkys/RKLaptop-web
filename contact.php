<?php include 'koneksi.php';
session_start();
error_reporting(0);
// Memeriksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']);

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
  <link rel="stylesheet" href="C:\xampp\htdocs\PROJECT-UTS-main\PROJECT UTS\fontawesome-free-6.3.0-web\css\all.css" />
  <!-- navbar -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/9f3246d2c8.js" crossorigin="anonymous"></script>
  <!-- Feather Icon -->
  <script src="https://unpkg.com/feather-icons"></script>
  <!-- My Style -->
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

  <!-- Contact Us Section -->
  <section id="ContactUs" class="contact">
    <div class="ContactUs-bg">
      <h2>Contact Us</h2>
    </div>
    <div class="TigaBagian">
      <div class="MapContactUs">
        <div class="map-responsive">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253190.19327239488!2d112.52524697378851!3d-7.454545965949547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e1a0848edcbf%3A0x3027a76e352bdf0!2sSidoarjo%20Regency%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1676916782179!5m2!1sen!2sid"></iframe>
        </div>
      </div>
      <div class="Meet-Us">
        <h3>Meet Us</h3>
        <p><i class="fas fa-phone"></i> +62 123 456 789</p>
        <p>
          <i class="fas fa-envelope"></i>
          <a href="mailto:21082010016@student.upnjatim.ac.id">21082010016@student.upnjatim.ac.id</a>
        </p>
        <p class="address">
          <i class="fas fa-map-marker-alt"></i> Jl. Rungkut Madya No.1, Gn.
          Anyar, Kec. Gn. Anyar, SBY, Jawa Timur 60294
        </p>
      </div>
      <div class="Pitch-Us">
        <h3>Pitch Us</h3>
        <p>
          Hello,<br />
          This website is developed by two students of UPN "Veteran" Jawa
          Timur and our email addresses are<br />
          <a href="mailto:21082010016@student.upnjatim.ac.id">21082010016@student.upnjatim.ac.id</a>
          and<br />
          <a href="mailto:21082010004@student.upnjatim.ac.id">21082010004@student.upnjatim.ac.id</a>.<br />
          If you have something to discuss, kindly send it to those emails.
        </p>
      </div>
    </div>
  </section>
  <script src="scriptLOGINREGISTER.js"></script>
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