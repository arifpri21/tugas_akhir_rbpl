<?php
// Memulai session
session_start();

// Hapus semua data session
session_unset();

// Hapus session
session_destroy();

// Arahkan pengguna ke halaman login setelah logout
header("Location: home.html");
exit;
?>

<script>
// Menampilkan alert setelah logout
alert("Anda telah logout");
</script>
