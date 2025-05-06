<?php
$accessToken = @$_SESSION['accessLogin'];

$getUser = "SELECT users.* FROM users 
            JOIN user_access_tokens ON users.id = user_access_tokens.user_id
            WHERE user_access_tokens.token='$accessToken'";
$result = $conn->query($getUser);
if ($result->num_rows == 0) {
    echo "<script>window.location.href = '{$menuAuth}/login.php';</script>";
    exit;

}
$id = $_GET['id'];

$getData = "SELECT * FROM jobs 
            WHERE id = ?";
$stmt = $conn->prepare($getData);
$stmt->bind_param("i", $id); 
$stmt->execute();
$stmt->get_result();
$job = $stmt->fetch_assoc();
?>

