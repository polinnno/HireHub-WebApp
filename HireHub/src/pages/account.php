<?php
global $conn;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../includes/header.php';
include '../includes/db_connection.php';

$candidateID = $_SESSION['CandidateID'] ?? 0;
$recruiterID = $_SESSION['RecruiterID'] ?? 0;
$defaultProfilePicture = "default.jpeg";


if ($candidateID) {
    // Show candidate account content
    include '../includes/account_candidate.php';
} elseif ($recruiterID) {
    // Show recruiter account content
    include '../includes/account_recruiter.php';
} else {
    header("Location: ../pages/login.php");
    exit();
}
include '../includes/footer.html';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/account.css">
    <title>Account</title>
</head>
<body>

</body>
</html>
