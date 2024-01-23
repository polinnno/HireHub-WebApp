<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidateID = $_POST['candidateID'] ?? 0;
    $jobID = $_POST['jobID'] ?? 0;

    $applicationQuery = "SELECT * FROM JobApplication WHERE CandidateID = $candidateID AND JobID = $jobID";
    $applicationResult = $conn->query($applicationQuery);

    if ($applicationResult->num_rows > 0) {
        // Candidate has already applied for this job.
        echo "already_applied";
    } else {
        // If candidate has not applied, insert a new record.
        $insertQuery = "INSERT INTO JobApplication (JobID, CandidateID, ApplicationDateTime) VALUES ($jobID, $candidateID, NOW())";
        if ($conn->query($insertQuery)) {
            echo "applied";
        } else {
            echo "error";
        }
    }

    $conn->close();
}
?>
