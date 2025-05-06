<?php
include "../../../init.php";
include "../getUser.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "<script>window.location.href = '{$menuprofile}/profile';</script>";
    exit;
}
$about = $_POST["about"];
$userID = $user['id'];
$about = $conn->real_escape_string(strip_tags($about));

$sql = "UPDATE users set about = ? WHERE id = ?";
$stmtUpdate = $conn->prepare($sql);
$stmtUpdate->bind_param("si", $about, $userID);

if (!$stmtUpdate->execute()) {
    $_SESSION['error_message'] = "Error: " . $sql . "<br>" . $conn->error;
    echo "<script>window.location.href = '{$menuProfileEditBio}';</script>";
    exit;
}

echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
exit;


?>