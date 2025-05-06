<?php
include "../../init.php";
include "../member/getUser.php";
$jobID = $_GET["id"];

//validate jobid di db
$query = "SELECT * FROM jobs WHERE id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $jobID); 
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows <= 0) {
    $_SESSION['error_message'] .= "Tidak ada lamaran";
    echo "<script>window.location.href = '{$menuJob}';</script>";
    exit;
}

// Mengecek apakah data dengan ID yang sama sudah ada di database
$query = "SELECT * FROM user_jobs WHERE user_id = ? and job_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userID, $jobID); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error_message'] .= "Kamu sudah melamar ke Lowongan ini";
    echo "<script>window.location.href = '{$menuJob}';</script>";
    exit;

} else {
    // Data tidak ditemukan, lakukan insert
    $insert_query = "INSERT INTO user_jobs (user_id, job_id, status) VALUES ($userID, $jobID,1)";
    if ($conn->query($insert_query) === TRUE) {
        $_SESSION['success_message'] .= "Terima kasih sudah melamar di Lowongan ini";
    } else {
        $_SESSION['error_message'] .= "Error inserting record: " . $conn->error;
    }
}
echo "<script>window.location.href = '{$menuHistory}';</script>";
exit;

?>

