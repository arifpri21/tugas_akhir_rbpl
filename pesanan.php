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

// Query untuk mengambil data pesanan
$sql_pesanan = "SELECT pesanan.*, produk.nama_produk, produk.harga, (pesanan.jumlah * produk.harga) AS total_harga, pesanan.status
                FROM pesanan
                INNER JOIN produk ON pesanan.id_produk = produk.id_produk
                WHERE pesanan.username = '$username'";


$result_pesanan = mysqli_query($conn, $sql_pesanan);

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Pesanan Saya</title>
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

  <div class="container">
    <h1>Tabel Pesanan</h1>
    <div class="table-responsive">
      <?php
      // Check if there are any orders
      if (mysqli_num_rows($result_pesanan) > 0) {
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID Pesanan</th>";
        echo "<th>ID Produk</th>";
        echo "<th>Nama Produk</th>";
        echo "<th>Jumlah</th>";
        echo "<th>Harga</th>";
        echo "<th>Total Harga</th>";
        echo "<th>Tanggal</th>";
        echo "<th>Status</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        // Iterasi melalui hasil query
        while ($row_pesanan = mysqli_fetch_assoc($result_pesanan)) {

          echo "<tr>";
          echo "<td>" . $row_pesanan['id_pesanan'] . "</td>";
          echo "<td>" . $row_pesanan['id_produk'] . "</td>";
          echo "<td>" . $row_pesanan['nama_produk'] . "</td>";
          echo "<td>" . $row_pesanan['jumlah'] . "</td>";
          echo "<td>" . $row_pesanan['harga'] . "</td>";
          echo "<td>" . $row_pesanan['total_harga'] . "</td>";
          echo "<td>" . $row_pesanan['tanggal'] . "</td>";
          echo "<td>" . $row_pesanan['status'] . "</td>"; 

          // Menampilkan tombol "Bayar" jika status belum dibayar
          if (isset($row_pesanan['status'])) {
              if ($row_pesanan['status'] == "Belum Di Bayar") {
                  echo "<td><a href='pembayaran.php?id_pesanan=" . $row_pesanan['id_pesanan'] . "' class='btn btn-primary'>Bayar</a></td>";
              } else {
                  echo "<td></td>"; // Kolom kosong jika status bukan "Belum Di Bayar"
              }
          } else {
              echo "<td>Status tidak tersedia</td>";
          }
      
          echo "</tr>";
      }
          echo "</tr>";
        
        echo "</tbody>";
        echo "</table>";
      } else {
        echo "<p>Tidak ada pesanan yang ditemukan</p>";
      }
      ?>
    </div>
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