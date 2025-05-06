<?php
include "../../init.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}



$phoneNumber = $_POST['phoneNumber'];

if (!preg_match('/^[0-9]{8,13}$/', $phoneNumber)) {
    $_SESSION['error_message'] = "Format nomor handphone tidak valid";
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}

if (strlen($phoneNumber) < 8 && strlen($phoneNumber) > 13){
    $_SESSION['error_message'] = "Panjang Telp tidak valid.";
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}

//validate ratelimit
$ipAddr = $_SERVER['REMOTE_ADDR'];
$attempt= 5;
$waktu = 69 * 60; //dalam menit (satu jam)
if (!isset($_SESSION['login_'.$ipAddr])){
    $_SESSION['login_'.$ipAddr] = 0;
    $_SESSION['lasttime_'.$ipAddr] = time();
}

if ($_SESSION['login_'.$ipAddr] >= $attempt){
    $timeawal = time() - $_SESSION['lasttime_'.$ipAddr];
    if($timeawal < $waktu){
        $_SESSION['error_message'] = "Anda sudah melebihi limit percobaan gagal.";
        echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
        exit;
    }
    $_SESSION['login_'.$ipAddr] = 0;
}

//validate session phone logedin
$cookieValue = $_COOKIE['accessLogin'] ?? null;
$stmt = $conn->prepare("SELECT * FROM user_access_tokens WHERE token = ? LIMIT 1");
$stmt->bind_param("s", $cookieValue);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    //sudah login
    echo "<script>window.location.href = '{$menuProfile}/profile/index.php';</script>";
    exit;
}


//validate if phoneNumber already registered
$stmt = $conn->prepare("SELECT * FROM users WHERE phone_number = ? LIMIT 1");
$stmt->bind_param("s", $phoneNumber);
$stmt->execute();
$result = $stmt->get_result();

//sudah terdaftar sebelumnya
if ($result->num_rows > 0) {
    
    $_SESSION['phone_number'] = $phoneNumber;
    echo "<script>window.location.href = '{$menuAuth}/otp.php';</script>";
    exit();
}

$user = $result->fetch_assoc();

$queryInsert = "INSERT INTO users (phone_number) VALUES ('$phoneNumber')";
    if ($conn->query($queryInsert) === TRUE) {
    echo "Nomor handphone berhasil ditambahkan!";
} else {
    $_SESSION['error_message'] = "Error: " . $queryInsert . "<br>" . $conn->error;
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}
$_SESSION['login_'.$ipAddr]++;
$_SESSION['phone_number'] = $phoneNumber;
echo "<script>window.location.href = '{$menuAuth}/otp.php';</script>";
exit;
