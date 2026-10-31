<?php
// Menghubungkan aplikasi PHP ke database MySQL
$host = "localhost";
$user = "root";
$pass = "";
$db = "blog";

$conn = mysqli_connect($host, $user, $pass, $db);

// Periksa apakah koneksi berhasil atau gagal
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>