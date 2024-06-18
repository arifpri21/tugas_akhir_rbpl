<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "creazy.co";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// cek koneksi
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}           
?>