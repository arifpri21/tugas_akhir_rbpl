<?php
// Memulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  // Pengguna belum login, arahkan ke halaman login
  header("Location: process_login.php");
  exit;
}

// Mengambil data pengguna dari database
include 'koneksi.php';

// Mengambil username dari session
$username = $_SESSION['username'];

// Query untuk mengambil data pengguna berdasarkan username
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

// Memeriksa apakah data pengguna ditemukan
if (mysqli_num_rows($result) > 0) {
  // Mengisi variabel $row dengan data pengguna
  $row = mysqli_fetch_assoc($result);
}

// Mengambil data produk dari database
$sql_produk = "SELECT * FROM produk";
$result_produk = mysqli_query($conn, $sql_produk);

// Mengambil nilai dari parameter query 'produk_id' jika ada
$produk_id = isset($_GET['produk_id']) ? $_GET['produk_id'] : '';

// Menutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="pemesanan.css">
  <link href='creazy.co.png' rel='shortcut icon'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg nav-light bg-blue">
    <a class="navbar-brand" href="#"><img src="creazy.co.png" width="110" height="40" class="mr-2"></a>
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
            Pesanan
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
          <?php if (!empty($row['profile_picture'])) : ?>
            <img src="<?php echo $row['profile_picture']; ?>" width="35" height="35" class="rounded-circle mr-2">
          <?php else : ?>
            <img src="gambar_profil.png" width="30" height="30" class="rounded-circle mr-2">
          <?php endif; ?>
          <?php echo $_SESSION['username']; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="edit_profil.php" style="display: flex; align-items: center;">
            <img src="edit_profil.png" width="15" height="15" style="vertical-align: middle; margin-right: 1px;">
            Edit Profil
          </a>
          <a class="dropdown-item" href="logout.php" style="display: flex; align-items: center;">
            <img src="logout.jpg" width="15" height="15" style="vertical-align: middle; margin-right: 3px;">
            Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div id="spanduk" style="background-color: #3498db; height: 350px; padding: 30px 0 0 60px;">
      <div class="text1" style="background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 10px; color: #ffffff;">
        Welcome<span><?php echo $_SESSION['username']; ?></span>
        <p>Selamat datang di situs web konveksi kami! Kami adalah platform yang menyediakan layanan lengkap. Temukan apparel terbaik dan atur desain Anda di web konveksi kami. <br>
          <a style="color: #ffffff; text-decoration: none;" href="#pesan" class="btn btn-secondary button_top">Pesan</a>
        </p>
      </div>
    </div>
  </div>

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
              <img src="gambar/promo/download.jpeg" alt="Promo Kaos" class="d-block w-100" width="100" height="150">
              <div class="carousel-caption d-none d-md-block">
                <h5>Diskon 10% untuk pembuatan kaos</h5>
              </div>
            </div>
            <div class="carousel-item">
              <img src="gambar/promo/promo_hoodie.jpeg" alt="Promo Anak" class="d-block w-100">
              <div class="carousel-caption d-none d-md-block">
                <h5>Bebas harga sablon untuk setiap ukuran desain</h5>
              </div>
            </div>
            <div class="carousel-item">
              <img src="gambar/promo/promo_hoodie2.jpeg" alt="Diskon 20%" class="d-block w-100">
              <div class="carousel-caption d-none d-md-block">
                <h5>Costum huruf tanpa minimal pembelian</h5>
              </div>
            </div>
            <div class="carousel-item">
              <img src="gambar/promo/jasa_bikin_pdh.jpeg" alt="Diskon 20%" class="d-block w-100">
              <div class="carousel-caption d-none d-md-block">
                <h5>Cashback hingga 125 Ribu</h5>
              </div>
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-previcon" aria-hidden="true"></span>
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

  <div class="container mt-5" id="pesan">
    <form action="pemesanan.php" method="get">
      <h2>Pilih Produk</h2>
      <table class="table">
        <thead>
          <tr>
            <th>Nama Produk</th>
            <th>Jenis Sablon</th>
            <th>Harga</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Loop melalui hasil query produk
          while ($row_produk = mysqli_fetch_assoc($result_produk)) {
            $nama_produk = $row_produk['nama_produk'];
            $jenis_sablon = $row_produk['jenis_sablon'];
            $harga = $row_produk['harga'];
            $id_produk = $row_produk['id_produk'];
            // Tampilkan baris tabel dengan data produk
            echo "<tr>";
            echo "<td>$nama_produk</td>";
            echo "<td>$jenis_sablon</td>";
            echo "<td>$harga</td>";
            echo "<td><button type='submit' name='produk_id' value='$id_produk' class='btn btn-primary'>Beli</button></td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </form>
  </div>


  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
      <li class="nav-item"><a href="about.php" class="nav-link px-2 text-muted">About</a></li>
    </ul>
    <p class="text-center text-muted">&copy; 2024 Creazy.co, Inc</p>
  </footer>
</body>

</html>