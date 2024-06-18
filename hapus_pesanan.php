<?php
// Memulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  // Pengguna belum login, arahkan ke halaman login
  header("Location: process_login.php");
  exit;
}

// Memeriksa apakah parameter id_pesanan telah diterima
if (isset($_GET['id_pesanan'])) {
  // Mengambil id_pesanan dari parameter
  $id_pesanan = $_GET['id_pesanan'];

  // Menghapus pesanan dari database
  include 'koneksi.php';
  $username = $_SESSION['username'];
  $sql = "DELETE FROM pesanan WHERE id_pesanan = '$id_pesanan' AND username = '$username'";
  mysqli_query($conn, $sql);
  mysqli_close($conn);

  // Menyimpan pesan notifikasi di session
  $_SESSION['pesan'] = "Pesanan berhasil dihapus.";


  // Redirect kembali ke halaman pesanan setelah penghapusan berhasil
  header("Location: pesanan.php");
  exit;
} else {
  // Jika parameter id_pesanan tidak ditemukan, arahkan kembali ke halaman pesanan
  header("Location: pesanan.php");
  exit;
}
