<?php
$servername = "localhost"; // Ganti dengan server database Anda jika perlu
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "lab_loker"; // Nama database yang dibuat sebelumnya
$host  = 'http://127.0.0.1:8004';


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
?>