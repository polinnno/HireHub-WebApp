<?php

include 'DB_connection.php';
// Include your database connection or any necessary dependencies

if (isset($_POST['scheduleInterview'])) {
    $candidateID = $_POST['candidateID'];
    $jobID = $_POST['jobID'];
    $interviewDateTime = $_POST['interviewDateTime']; // Assuming this is the date-time input value
    $location = $_POST['interviewLocation']; // Assuming this is the location input value


    $formattedInterviewDateTime = date("Y-m-d H:i:s", strtotime($interviewDateTime));


    if (strtotime($formattedInterviewDateTime) < time()) {
        // If interview date and time are in the past -- show error.
        echo 'error';
        exit();
    }

    $sql = "INSERT INTO Interview (CandidateID, JobID, InterviewDateTime, Location) VALUES ($candidateID, $jobID, '$formattedInterviewDateTime', '$location')";

    error_log("SQL Query: $sql");

    global $conn;

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
