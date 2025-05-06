<?php
include "../../../init.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    header('Location: '.$menuAdmin.'/auth/login.php');
    exit;
}

$email = @$_POST['email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Format email tidak valid.";
    header('Location: ' . $host . '/authentication/lab-1/');
    exit;
}

if (strlen($email) > 100){
    $_SESSION['error_message'] = "Panjang email tidak valid.";
    header('Location: ' . $host . '/authentication/lab-1/');
    exit;
}

if (strlen($_POST['password']) > 8 && strlen($_POST['password']) < 50){
    $_SESSION['error_message'] = "Panjang Passowrd tidak minimal 8 dan panjang 50.";
    header('Location: ' . $host . '/authentication/lab-1/');
    exit;
}

$password = sha1(@$_POST['password']);


// Siapkan statement untuk cegah SQL Injection
$stmt = $conn->prepare("SELECT username, password FROM admin WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: '.$menuAdmin.'/auth/login.php' );
    exit();
}

$user = $result->fetch_assoc();
//generate session login
$accessLogin = bin2hex(random_bytes(32));
$_SESSION['accessLogin'] = $accessLogin;
$userID = $user['id'];
//insert to user_access_tokens
$sql = "INSERT INTO admin_access_tokens (admin_id, token) VALUES ('$userID','$accessLogin')";
$conn->query($sql);
header('Location: '.$menuAdminApplicant);
exit;
