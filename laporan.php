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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $tanggal_awal = $_POST['tanggal_awal'];
  $tanggal_akhir = $_POST['tanggal_akhir'];

  // Query untuk mengambil data pesanan berdasarkan rentang tanggal
  $sql_pesanan = "SELECT * FROM pesanan WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
  $result_pesanan = mysqli_query($conn, $sql_pesanan);
} else {
  // Query untuk mengambil semua data pesanan jika tidak ada tanggal yang dipilih
  $sql_pesanan = "SELECT * FROM pesanan";
  $result_pesanan = mysqli_query($conn, $sql_pesanan);
}

// Menutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Laporan Pesanan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="pemesanan.css">
  <link href='creazy.co.png' rel='shortcut icon'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
  <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
  <script>
    function cetakPDF() {
      const element = document.querySelector(".container");
      html2pdf()
        .set({
          margin: [0, 0, 0, 0],
          filename: 'laporan_pesanan.pdf',
          image: {
            type: 'jpeg',
            quality: 0.98
          },
          html2canvas: {
            dpi: 192,
            letterRendering: true
          },
          jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
          }
        })
        .from(element)
        .save();
    }
  </script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><img src="creazy.co.png" width="110" height="40" class="mr-2"></a>
    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard_admin.php" style="display: flex; align-items: top;">
            <img src="home.png" width="20" height="20" style="margin-right: 5px;">Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="laporan.php" style="display: flex; align-items: top;">
            <img src="pesan.png" width="20" height="20" style="margin-right: 3px;">Laporan
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
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php if (!empty($row['profile_picture'])) : ?>
            <img src="<?php echo $row['profile_picture']; ?>" width="35" height="35" class="rounded-circle mr-2">
          <?php else : ?>
            <img src="gambar_profil.png" width="30" height="30" class="rounded-circle mr-2">
          <?php endif; ?>
          <?php echo $_SESSION['username']; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="edit_profil.php" style="display: flex; align-items: center;">
            <img src="edit_profil.png" width="15" height="15" style="vertical-align: middle; margin-right: 1px;">Edit Profil
          </a>
          <a class="dropdown-item" href="logout.php" style="display: flex; align-items: center;">
            <img src="logout.jpg" width="15" height="15" style="vertical-align: middle; margin-right: 3px;">Logout
          </a>
        </div>
      </div>
    </div>
  </nav>
  <div class="container mt-5">
    <h2 class="mt-5">Laporan Pesanan</h2>
    <form method="POST" action="laporan.php">
      <div class="form-group">
        <label for="tanggal_awal">Tanggal Awal</label>
        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required>
      </div>
      <div class="form-group">
        <label for="tanggal_akhir">Tanggal Akhir</label>
        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
      </div>
      <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
      <button type="button" class="btn btn-success" onclick="cetakPDF()">Cetak PDF</button>
    </form>
    <div class="table-responsive mt-4">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID Pesanan</th>
            <th>Produk</th>
            <th>Jenis Sablon</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total Harga</th>
            <th>Desain</th>
            <th>Nama</th>
            <th>No. Telepon</th>
            <th>Tanggal</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result_pesanan->num_rows > 0) {
            // Menampilkan data setiap baris
            while ($row_pesanan = $result_pesanan->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row_pesanan["id_pesanan"] . "</td>";
              echo "<td>" . $row_pesanan["nama_produk"] . "</td>";
              echo "<td>" . (isset($row_pesanan["jenis_sablon"]) ? $row_pesanan["jenis_sablon"] : '') . "</td>";
              echo "<td>" . $row_pesanan["jumlah"] . "</td>";
              echo "<td>" . $row_pesanan["harga"] . "</td>";
              echo "<td>" . $row_pesanan["total_harga"] . "</td>";
              echo "<td>" . $row_pesanan["nama"] . "</td>";
              echo "<td>" . $row_pesanan["no_telp"] . "</td>";
              echo "<td>" . $row_pesanan["tanggal"] . "</td>";
              echo "<td>" . (isset($row_pesanan["status"]) ? $row_pesanan["status"] : 'Tidak ada status') . "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='11'>Tidak ada data</td></tr>";
          }
          ?>
        </tbody>
        </tbody>
      </table>
    </div>
  </div>
  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Pricing</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
    </ul>
    <p class="text-center text-muted">© 2024 Creazy Co</p>
  </footer>
</body>

</html>