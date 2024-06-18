<?php
// Mulai session
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  // Jika belum, arahkan kembali ke halaman login
  header("Location: process_login.php");
  exit;
}

// Sertakan file koneksi ke database
include 'koneksi.php';

// Ambil username dari sesi
$username = $_SESSION['username'];

// Jika ada ID pesanan yang dikirim melalui URL
if (isset($_GET['id_pesanan'])) {
  // Ambil ID pesanan dari URL
  $id_pesanan = $_GET['id_pesanan'];

  // Query untuk mengambil informasi pesanan dari tabel pesanan
  $sql_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan' AND username = '$username'";
  $result_pesanan = mysqli_query($conn, $sql_pesanan);

  // Periksa apakah pesanan ditemukan
  if (mysqli_num_rows($result_pesanan) > 0) {
    // Ambil data pesanan dari hasil query
    $row_pesanan = mysqli_fetch_assoc($result_pesanan);

    // Jika formulir pembayaran dikirimkan
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Ambil data dari formulir pembayaran
      $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];
      $temp_name = $_FILES['bukti_pembayaran']['tmp_name'];

      // Pindahkan file bukti pembayaran ke direktori yang ditentukan
      move_uploaded_file($temp_name, "uploads/$bukti_pembayaran");

      // Update tabel pesanan dengan data bukti pembayaran dan status pembayaran
      $update_sql = "UPDATE pesanan SET bukti_pembayaran = '$bukti_pembayaran', status = 'Sudah Dibayar' WHERE id_pesanan = '$id_pesanan'";
      if (mysqli_query($conn, $update_sql)) {
        echo "Pembayaran berhasil diunggah.";
      } else {
        echo "Gagal mengunggah pembayaran: " . mysqli_error($conn);
      }
    }
?>

    <!DOCTYPE html>
    <html>

    <head>
      <meta charset="UTF-8">
      <title>Pembayaran</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <link rel="stylesheet" href="pemesanan.css">
      <link href='creazy.co.png' rel='shortcut icon'>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </head>

    <body>

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
          <h1 class="mb-4">Pembayaran</h1>
          <h2 class="mb-3">Informasi Pesanan</h2>
          <p>ID Pesanan: <?php echo $row_pesanan['id_pesanan']; ?></p>
          <!-- Tampilkan informasi pesanan lainnya seperti ID produk, jumlah, dll. -->

          <h2 class="mb-3">Informasi Pembayaran</h2>
          <p>Jumlah yang harus dibayarkan: <?php echo $row_pesanan['total_harga']; ?></p>
          <p>Nomor Rekening: <img src="gambar/logo_bank.png" height="150"> 120-711-3989 </p>
          <p>Nama : Zakya Falih Darmawan </p>

          <!-- Tambahkan formulir untuk mengunggah bukti pembayaran -->
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id_pesanan=' . $id_pesanan; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="bukti_pembayaran">Unggah Bukti Pembayaran</label>
              <input type="file" class="form-control-file" id="bukti_pembayaran" name="bukti_pembayaran" required>
            </div>
            <button type="submit" class="btn btn-primary">Unggah Bukti Pembayaran</button>
          </form>

        </div>
      </body>

    </html>
<?php
  } else {
    echo "Pesanan tidak ditemukan atau tidak sah.";
  }
} else {
  echo "ID pesanan tidak valid.";
}
?>