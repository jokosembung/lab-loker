<?php
include "../../../init.php";
include "../getUser.php";

$id = $_GET['id'];
if (!$id || !is_numeric($id)) {
    $_SESSION['error_message_skill'] = "Invalid" ;
    echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
    exit;
}

$sql = "DELETE FROM skills WHERE id = ? and user_id = ?";

$stmtUpdate = $conn->prepare($sql);
$stmtUpdate->bind_param("ii", $id, $userID);

if (!$stmtUpdate->execute()) {
    $_SESSION['error_message_skill'] = "Error: " . $sql . "<br>" . $conn->error;

    echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
    exit;

}

echo "<script>window.location.href = '{$menuProfile}/profile';</script>";
exit;
