<?php
/*
START proses_login
IF request_method adalah POST THEN
    GET username dari data POST
    GET password dari data POST
    
    IF username kosong ATAU password kosong THEN
        SET pesan_error = "Username dan password diperlukan!"
        REDIRECT ke index page
        KELUAR
    END IF
    
    pengguna = verify_login(username, password)
    
    IF pengguna ada THEN
        SET session user_id = ID pengguna
        SET session username = username pengguna
        SET session logged_in = benar
        REDIRECT ke halaman utama
    ELSE
        SET pesan_error = "Username atau password tidak valid!"
        REDIRECT ke index page
    END IF
END IF
END login_process
*/

require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';
    
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan password harus diisi!";
        header("Location: index.html");
        exit();
    }
    
    $user = verify_login($username, $password);
    
    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;
        
        // Redirect ke halaman home
        header("Location: /Home/");
        exit();
    } else {
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: index.html");
        exit();
    }
}
?>

