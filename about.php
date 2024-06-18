<?php
// Memulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  // Pengguna belum login, arahkan ke halaman login
  header("Location: process_login.php");
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="pemesanan.css">
  <link href='logoweb.png' rel='shortcut icon'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script>
    function swapAsalTujuan() {
      var asal = document.getElementById('asal').value;
      var tujuan = document.getElementById('tujuan').value;

      document.getElementById('asal').value = tujuan;
      document.getElementById('tujuan').value = asal;
    }
  </script>

</head>

<body>
  <nav class="navbar navbar-expand-lg nav-light bg-blue">
    <a class="navbar-brand" href="#"><img src="ripan.png" width="110" height="40" class="mr-2"></a>
    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link nav-link-home" href="dashboard.php" style="display: flex; align-items: top;">
            <img src="home.png" width="20" height="20" style="margin-right: 5px;">
            Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-pesanan" href="pesanan.php" style="display: flex; align-items: top;">
            <img src="pesan.png" width="20" height="20" style="margin-right: 3px;">
            Pesanan Saya
          </a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Cari" aria-label="Cari">
        <button type="button" class="btn btn-outline-light">Cari</button>
      </form>
    </div>
    <div class="ml-auto">
      <div class="dropdown">
        <a class="nav-link dropdown-toggle dropdown-username" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="gambar_profil.png" width="30" height="30" class="rounded-circle mr-2">
          <?php echo $_SESSION['username']; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="edit_profil.php" style="display: flex; align-items: center;">
            <img src="edit_profil.png" width="15" height="15" style="vertical-align: middle; margin-right: 1px;">
            Edit Profil
          </a>
          <a class="dropdown-item" href="process_login.php" style="display: flex; align-items: center;">
            <img src="login.png" width="15" height="15" style="vertical-align: middle; margin-right: 3px;">
            Login
          </a>
          <a class="dropdown-item" href="logout.php" style="display: flex; align-items: center;">
            <img src="logout.png" width="15" height="15" style="vertical-align: middle; margin-right: 1px;">
            Logout
          </a>
        </div>
      </div>
    </div>
  </nav>


  <div class="container mt-2">
    <h2>Promo-promo Spesial</h2>
    <p>Nikmati penawaran promo terbaru kami:</p>
    <div class="card">
      <div class="card-body">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
          </ol>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="promo_bali.jpeg" alt="Promo Bali" class="d-block w-100">
              <div class="carousel-caption d-none d-md-block">
                <h5>Diskon 50% untuk penerbangan Jakarta - Bali</h5>
              </div>
            </div>
            <div class="carousel-item">
              <img src="promo_anak.jpg" alt="Promo Anak" class="d-block w-100">
              <div class="carousel-caption d-none d-md-block">
                <h5>Tiket gratis untuk 1 anak usia di bawah 12 tahun</h5>
              </div>
            </div>
            <div class="carousel-item">
              <img src="diskon20.jpg" alt="Diskon 20%" class="d-block w-100">
              <div class="carousel-caption d-none d-md-block">
                <h5>Diskon hingga 20% untuk penerbangan domestik</h5>
              </div>
            </div>
            <div class="carousel-item">
              <img src="promo_cashback.jpeg" alt="Diskon 20%" class="d-block w-100">
              <div class="carousel-caption d-none d-md-block">
                <h5>Cashback hingga 200 Ribu</h5>
              </div>
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <h2>Tentang riPan Air</h2>
    <p>riPan Air adalah perusahaan maskapai terpercaya yang menyediakan pengalaman perjalanan udara yang andal dan nyaman. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    <p>Di riPan Air, kami berusaha untuk menawarkan layanan dan nilai yang luar biasa kepada pelanggan kami. Baik Anda melakukan perjalanan untuk bisnis atau liburan, kami bertujuan untuk membuat perjalanan Anda menyenangkan dan nyaman.</p>
    <p>Tim profesional kami bekerja dengan tekun untuk menjamin standar keselamatan dan kepuasan pelanggan yang tinggi. Kami mengoperasikan armada pesawat modern dan menyediakan berbagai layanan untuk memenuhi berbagai kebutuhan perjalanan.</p>
    <p>Untuk pertanyaan, pemesanan, atau bantuan, silakan hubungi tim dukungan pelanggan kami yang tersedia 24/7 untuk membantu Anda. Kami menghargai masukan Anda dan terus berusaha untuk meningkatkan layanan kami.</p>
  </div>


  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="dashboard.php" class="nav-link px-2 text-muted">Home</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
    </ul>
    <p class="text-center text-muted">&copy; 2023 riPan Air, Inc</p>
  </footer>
  </div>
</body>

</html>