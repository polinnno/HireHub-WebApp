<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$json_data = file_get_contents("php://input");
$post_data = json_decode($json_data);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../includes/db_connection.php';

    $candidateID = $_POST['candidateID'] ?? 0;
    $jobID = $_POST['jobID'] ?? 0;

    if (isset($_POST['applyForJob'])) {
        applyForJob();
    } elseif (isset($_POST['withdrawApplication'])) {
        withdrawFromJob();
    } else {
        $favoritesQuery = "SELECT * FROM Favorites 
                            WHERE CandidateID = $candidateID AND JobID = $jobID";
        $favoritesResult = $conn->query($favoritesQuery);
        if ($favoritesResult->num_rows > 0) {
            // Record exists, delete.
            $deleteQuery = "DELETE FROM Favorites WHERE CandidateID = $candidateID AND JobID = $jobID";
            $conn->query($deleteQuery);
            echo "removed";
        } else {
            // Record doesn't exist, insert.
            $insertQuery = "INSERT INTO Favorites (CandidateID, JobID) VALUES ($candidateID, $jobID)";
            $conn->query($insertQuery);
            echo "added";
        }
        $conn->close();
    }
}


function applyForJob() {
    global $conn;

    $candidateID = $_POST['candidateID'] ?? 0;
    $jobID = $_POST['jobID'] ?? 0;
    $checkApplicationQuery = "SELECT * FROM Application WHERE CandidateID = $candidateID AND JobID = $jobID";
    $checkApplicationResult = $conn->query($checkApplicationQuery);

    if ($checkApplicationResult->num_rows == 0) {
        $insertQuery = "INSERT INTO Application (JobID, CandidateID, ApplicationDateTime) 
                        VALUES ($jobID, $candidateID, NOW())";
        $conn->query($insertQuery);
        echo "added";
    }
}


function withdrawFromJob() {

    global $conn;
    include '../includes/db_connection.php';

    $candidateID = $_POST['candidateID'] ?? 0;
    $jobID = $_POST['jobID'] ?? 0;

    // Check if the application exists before withdrawal.
    $checkApplicationQuery = "SELECT * FROM Application WHERE CandidateID = $candidateID AND JobID = $jobID";
    $checkApplicationResult = $conn->query($checkApplicationQuery);

    if ($checkApplicationResult->num_rows > 0) {
        // Application exists -- withdraw.
        $withdrawApplicationQuery = "DELETE FROM Application WHERE CandidateID = $candidateID AND JobID = $jobID";
        $conn->query($withdrawApplicationQuery);

        // Remove associated interview records.
        $withdrawInterviewQuery = "DELETE FROM Interview WHERE CandidateID = $candidateID AND JobID = $jobID";
        $conn->query($withdrawInterviewQuery);

        echo 'withdrawn';
    }
}

function isJobInFavorites($candidateID, $jobID): bool {
    global $conn;
    include '../includes/db_connection.php';

    $favoritesQuery = "SELECT * FROM Favorites WHERE CandidateID = $candidateID AND JobID = $jobID";
    $favoritesResult = $conn->query($favoritesQuery);

    $conn->close();
    return $favoritesResult->num_rows > 0;
}