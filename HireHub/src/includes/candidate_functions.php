<?php
// Include database connection here if not included in each function separately

function getCandidateDetails($candidateID) {
    global $conn;

    // Fetch candidate details from the database
    $sql = "SELECT * FROM Candidate WHERE CandidateID = $candidateID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return [];
}

function getJobsAppliedByCandidate($candidateID) {
    global $conn;

    $sql = "SELECT Job.*, Application.ApplicationDateTime
            FROM Job
            INNER JOIN Application ON Job.JobID = Application.JobID
            WHERE Application.CandidateID = $candidateID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

function getFavoritedJobs($candidateID) {
    global $conn;

    // Get favorite jobs for the given candidate from the Favorites table
    $query = "SELECT Job.* FROM Job
              INNER JOIN Favorites ON Job.JobID = Favorites.JobID
              WHERE Favorites.CandidateID = $candidateID";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $favoritedJobs = [];
        while ($row = $result->fetch_assoc()) {
            $favoritedJobs[] = $row;
        }

        return $favoritedJobs;
    } else {
        return [];
    }
}
?>
