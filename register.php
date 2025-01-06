<?php
/*START registration_process
    IF request_method is POST THEN
        GET username from POST data
        GET password from POST data
        GET full_name from POST data
        GET email from POST data
        GET phone_number from POST data
        GET address from POST data
        
        IF username is empty OR password is empty THEN
            SET error_message = "Username dan password diperlukan!"
            REDIRECT to index page
            EXIT
        END IF
        
        TRY
            // Check if username already exists
            query = prepare("SELECT id FROM buyers WHERE username = username")
            execute query
            
            IF username exists THEN
                SET error_message = "Username sudah digunakan!"
                REDIRECT to index page
                EXIT
            END IF
            
            // Insert new user
            query = prepare("INSERT INTO buyers (username, password, full_name, email, phone, address)")
            bind_parameters(username, password, full_name, email, phone_number, address)
            
            IF query execution successful THEN
                SET success_message = "Pendaftaran berhasil! Silakan login."
                REDIRECT to index page
            ELSE
                THROW error "Pendaftaran gagal"
            END IF
            
        CATCH error
            LOG error_message
            SET error_message = "Terjadi kesalahan: " + error_message
            REDIRECT to index page
            
        FINALLY
            CLOSE all database statements
        END TRY
        
    ELSE
        REDIRECT to index page
    END IF
END registration_process
*/
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';
    $nama_lengkap = $_POST["nama_lengkap"] ?? '';
    $email = $_POST["email"] ?? '';
    $no_telp = $_POST["no_telp"] ?? '';
    $alamat = $_POST["alamat"] ?? '';
    
    // Validasi input wajib
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan password harus diisi!";
        header("Location: index.php");
        exit();
    }
    
    try {
        // Cek apakah username sudah ada
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM pembeli WHERE username = ?");
        if (!$check_stmt) {
            throw new Exception("Gagal memeriksa username");
        }
        
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $_SESSION['error'] = "Username sudah digunakan!";
            header("Location: index.php");
            exit();
        }
        mysqli_stmt_close($check_stmt);
        
        // Siapkan query untuk insert dengan semua field
        $insert_query = "INSERT INTO pembeli (username, password, nama_lengkap, email, no_telp, alamat) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        
        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan query pendaftaran");
        }
        
        // Bind parameter dan jalankan query
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
            header("Location: index.php");
            exit();
        } else {
            throw new Exception("Gagal melakukan pendaftaran");
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        error_log("Error in register.php: " . $e->getMessage());
        header("Location: index.php");
        exit();
    } finally {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
    }
}

// Jika bukan POST request, redirect ke halaman utama
header("Location: index.php");
exit();
?>
