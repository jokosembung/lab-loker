<?php
include "../../../init.php";
include "../getUser.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
    exit;

}

$skill = $_POST["skill"];
if(strlen($skill) < 1 || strlen($skill) > 50){
    $_SESSION['error_message'] = "Minimal 1 karakter maksimal 50";
    echo "<script>window.location.href = '{$menuProfileAddSkill}';</script>";
    exit;
}

$skill = $conn->real_escape_string(strip_tags($skill));
$sql = "INSERT INTO skills (user_id, skill) VALUES (?, ?)";

$stmtUpdate = $conn->prepare($sql);
$stmtUpdate->bind_param("is", $userID, $skill);

if (!$stmtUpdate->execute()) {
    $_SESSION['error_message'] = "Error: " . $sql . "<br>" . $conn->error;
    echo "<script>window.location.href = '{$menuProfileAddSkill}';</script>";
    exit;

}

$conn->close();

echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
exit;

?>