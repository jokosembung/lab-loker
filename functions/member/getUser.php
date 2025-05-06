<?php
$accessToken = $_SESSION['accessLogin'];

$getUser = "SELECT users.* FROM users 
            JOIN user_access_tokens ON users.id = user_access_tokens.user_id
            WHERE user_access_tokens.token='$accessToken'";
$result = $conn->query($getUser);
if ($result->num_rows == 0) {
    header('Location: '.$host);
    exit();
}
$user = $result->fetch_assoc();
$avatar = $user['avatar'];
$userID = $user['id'];

?>