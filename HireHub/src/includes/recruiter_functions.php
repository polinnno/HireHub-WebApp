<?php
error_reporting(E_ALL);
include 'db_connection.php';

global $conn;
error_log("opening recruiter functions");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recruiterID = $_POST['recruiterID'] ?? 0;


    if (isset($_POST['deleteContact'])) {
        // Contact deletion

        $contactID = $_POST['contactID'] ?? 0;
        $deleted = deleteContact($contactID);

        if ($deleted) {
            echo 'deleted';
        } else {
            echo 'error';
        }
    }
    elseif (isset($_POST['deleteJob'])) {
        $jobID = $_POST['jobID'] ?? 0;

        // Job deletion
        $deleted = deleteJob($recruiterID, $jobID);

        if ($deleted) {
            echo 'deleted';
        } else {
            echo 'error';
        }
    }
}

function deleteJob($recruiterID, $jobID) {

    include 'db_connection.php';

    $sql = "SELECT Photo FROM job WHERE RecruiterID = $recruiterID AND JobID = $jobID";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Delete the image file
        $row = $result->fetch_assoc();
        $photoFilename = $row['Photo'];

        $imagePath = "../media/position_cover/$photoFilename";

        if (file_exists($imagePath)) {
            error_log("well at least it exists!");
            if (unlink($imagePath)){
                error_log("AND it unlinks!!!");
            }
        }

        // Delete the record from the job table
        $sqlDelete = "DELETE FROM job WHERE RecruiterID = $recruiterID AND JobID = $jobID";
        $resultDelete = $conn->query($sqlDelete);

        $conn->close();

        return $resultDelete;
    }

    return false;
}


function getRecruiterDetails($recruiterID) {
    global $conn;

    $recruiterID = mysqli_real_escape_string($conn, $recruiterID);

    $query = "SELECT * FROM recruiter WHERE RecruiterID = '$recruiterID'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

function getRecruiterContacts($recruiterID): array {
    global $conn;

    $recruiterID = mysqli_real_escape_string($conn, $recruiterID);

    $query = "SELECT * FROM contact WHERE RecruiterID = '$recruiterID'";
    $result = mysqli_query($conn, $query);

    $contacts = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $contacts[] = $row;
        }
    }

    return $contacts;
}

function getRecruiterJobs($recruiterID): array {
    global $conn;

    $recruiterID = mysqli_real_escape_string($conn, $recruiterID);

    $query = "SELECT * FROM job WHERE RecruiterID = '$recruiterID'";
    $result = mysqli_query($conn, $query);

    $jobs = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row;
        }
    }

    return $jobs;
}

function isJobInFavorites($recruiterID, $jobID): bool {
    global $conn;

    $recruiterID = mysqli_real_escape_string($conn, $recruiterID);
    $jobID = mysqli_real_escape_string($conn, $jobID);

    $query = "SELECT * FROM favorites WHERE RecruiterID = '$recruiterID' AND JobID = '$jobID'";
    $result = mysqli_query($conn, $query);

    return $result && mysqli_num_rows($result) > 0;
}

function getJobPostingCountForContact($contactID) {
    include '../includes/db_connection.php';

    try {
        $stmt = $conn->prepare("SELECT COUNT(*) AS jobPostingCount FROM job WHERE ContactID = ?");
        $stmt->bind_param('i', $contactID);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['jobPostingCount'];
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return 0; // Error -- return 0.
    } finally {
        $stmt->close();
        $conn->close();
    }
}
function deleteContact($contactID) {
    include '../includes/db_connection.php';

    // Check if the contact exists
    $checkContactQuery = "SELECT * FROM contact WHERE ID = ?";
    $checkContactStmt = $conn->prepare($checkContactQuery);
    $checkContactStmt->bind_param('i', $contactID);
    $checkContactStmt->execute();
    $checkContactStmt->store_result();

    if ($checkContactStmt->num_rows > 0) {
        // Contact exists, proceed with deletion
        $deleteContactQuery = "DELETE FROM contact WHERE ID = $contactID";
        $conn->query($deleteContactQuery);
        return true;
    }
}
?>
