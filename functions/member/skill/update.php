<?php
include "../../../init.php";
include "../getUser.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
    exit;

}

$id = $_GET['id'];
$skill = $_POST['skill'];

if (!$id || !is_numeric($id)) {
    $_SESSION['error_message_skill'] = "Invalid userid" ;
    echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
    exit;
}

$skill = $conn->real_escape_string(strip_tags($skill));

$sql = "UPDATE skills set skill = ? WHERE id = ? AND user_id= ?";

$stmtUpdate = $conn->prepare($sql);
$stmtUpdate->bind_param("sii", $skill, $id, $userID);

if (!$stmtUpdate->execute()) {
    $_SESSION['error_message_skill'] = "Error: " . $sql . "<br>" . $conn->error;
    echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
    exit;
}


echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
exit;
?>