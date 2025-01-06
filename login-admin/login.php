<?php
require 'koneksi.php';

$username = $_POST["username"];
$password = $_POST["password"];

$query_sql = "SELECT * FROM admin_data WHERE username = ? AND password = ?";
$stmt = mysqli_prepare($conn, $query_sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        header("Location: /dashboard-admin/");
        exit();
    } else {
        echo "Login Gagal: Username atau Password salah.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Terjadi kesalahan pada query.";
}

mysqli_close($conn);
?>
