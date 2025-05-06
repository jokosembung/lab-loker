<?php

$id = $_GET['id'];

$getData = "SELECT * FROM skills WHERE id = ? and user_id = ?";

$resultSkill = $conn->prepare($getData);
$resultSkill->bind_param("ii", $id, $userID);
$resultSkill->execute();
$result = $resultSkill->get_result();
$data = $result->fetch_assoc();

?>