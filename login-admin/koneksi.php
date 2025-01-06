<?php
$servername = "localhost";
$database = "advcshop_user";
$username = "advcshop";
$password = "osttamvan123";

$conn = mysqli_connect($servername, $username, $password , $database);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
} else {
    echo "";
}
