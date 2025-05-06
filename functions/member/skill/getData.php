<?php
include "../getUser.php";
$id = $_GET['id'];

if (!$id || !is_numeric($id)) {
    $_SESSION['error_message_skill'] = "Invalid userid" ;
    echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
    exit;
}

$getData = "SELECT * FROM skills 
            WHERE id = ? and user_id = ?";

$resultSkill = $conn->query($getData);
$resultSkill->bind_param("ii", $id, $userID);
$data = $resultSkill->fetch_assoc();

?>