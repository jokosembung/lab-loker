<?php
include "../../init.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}


$phoneNumber = $_POST['phoneNumber'];
$otp = $_POST['otp'];

//sanitasi input
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

if (!is_numeric($otp) || strlen($otp) != 4) {
    $_SESSION['error_message'] = "OTP tidak valid";
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}

//ratelimit otp jika fail
//validate ratelimit
$ipAddr = $_SERVER['REMOTE_ADDR'];
$attempt= 5;
$waktu = 69 * 60; //dalam menit (satu jam)
if (!isset($_SESSION['otp_'.$ipAddr.'_'.$phoneNumber])){
    $_SESSION['otp_'.$ipAddr.'_'.$phoneNumber] = 0;
    $_SESSION['lastotptime_'.$ipAddr.'_'.$phoneNumber] = time();
}

if ($_SESSION['otp_'.$ipAddr.'_'.$phoneNumber] >= $attempt){
    $timeawal = time() - $_SESSION['lastotptime_'.$ipAddr.'_'.$phoneNumber];
    if($timeawal < $waktu){
        $_SESSION['error_message'] = "Anda sudah melebihi limit percobaan gagal.";
        echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
        exit;
    }
    $_SESSION['login_'.$ipAddr.'_'.$phoneNumber] = 0;
}


//validate otp
$validateOTP = "SELECT * from otp where otp=? and is_active = 0";
$stmt = $conn->prepare($validateOTP);
$stmt->bind_param("s", $otp);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
//    update is active
    $updateIsActive = "UPDATE otp SET is_active = 1 where otp= ? ";
    $updateStmt = $conn->prepare($updateIsActive);
    $updateStmt->bind_param("s", $otp);
    $updateStmt->execute();
}

//otp sudah pernah active tidak boleh
$rowotp = $result->fetch_assoc();
if ($rowotp['is_active'] == 1){
    $_SESSION['error_message'] = "OTP tidak valid";
    $_SESSION['otp_'.$ipAddr.'_'.$phoneNumber]++;
    $_SESSION['lastotptime_'.$ipAddr.'_'.$phoneNumber] = time();
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}

//get user id
$getUser = "SELECT * from users where phone_number= ? ";
$stmt = $conn->prepare($getUser);
$stmt->bind_param("s", $phoneNumber);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;
}
$user = $result->fetch_assoc();
//generate session login
$accessLogin = bin2hex(random_bytes(32));
$_SESSION['accessLogin'] = $accessLogin;
$userID = $user['id'];
//insert to user_access_tokens
$sql = "INSERT INTO user_access_tokens (user_id, token) VALUES ('$userID','$accessLogin')";
$conn->query($sql);

$conn->close();

echo "<script>window.location.href = '{$menuProfile}/profile/index.php';</script>";
exit;