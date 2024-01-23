<?php
global $conn;
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
$recruiterID = $_SESSION['RecruiterID'] ?? 0;

include '../includes/DB_connection.php';

$jobID = $_GET['job_id'] ?? 0;
$position = '';
$jobType = '';
$salary = '';
$experience = '';
$contactID = '';

// Get old job data.
if ($jobID) {
    $jobQuery = "SELECT * FROM Job WHERE JobID = $jobID";
    $jobResult = $conn->query($jobQuery);

    if ($jobResult && $jobResult->num_rows > 0) {
        $jobData = $jobResult->fetch_assoc();
        $position = $jobData['Position'];
        $jobType = $jobData['JobType'];
        $salary = $jobData['Salary'];
        $experience = $jobData['MinExperience'];
        $contactID = $jobData['ContactID'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: edit_job_logic.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/add_position.css">
    <title>Edit Job</title>
</head>
<body>
<h1>Edit Job</h1>
<button id="back" onclick="goBack()">&#x2190; back</button>

<div class="form-container">
    <form action="../includes/edit_job_logic.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="jobID" value="<?= $jobID ?>">

        <div class="single-line-input">
            <label for="position">Position:</label>
            <input type="text" name="position" value="<?= $position ?>" required><br>
        </div>
        <div class="single-line-input">
            <label for="jobType">Job Type:</label>
            <select name="jobType">
                <?php
                // Add job types from the database
                $jobTypesQuery = "SELECT * FROM JobTypes";
                $jobTypesResult = $conn->query($jobTypesQuery);

                while ($jobTypeRow = $jobTypesResult->fetch_assoc()) {
                    $selected = ($jobTypeRow['JobType'] == $jobType) ? 'selected' : '';
                    echo "<option value=\"{$jobTypeRow['JobType']}\" $selected>{$jobTypeRow['JobType']}</option>";
                }
                ?>
            </select><br>
        </div>

        <div class="two-column-input">
            <div class="column">
                <label for="salary">Salary (€):</label>
                <input type="number" name="salary" value="<?= $salary ?>" required><br>
            </div>
            <div class="column">
                <label for="experience">Minimum Years of Experience:</label>
                <input type="number" name="experience" value="<?= $experience ?>" min="0" required><br>
            </div>
        </div>

        <div class="single-line-input">
            <label for="contact">Select Contact:</label>
            <select name="contact" required>
                <?php
                // Add contacts from the database
                $contactsQuery = "SELECT ID, FirstName, LastName, Position FROM Contact WHERE RecruiterID = $recruiterID";
                $contactsResult = $conn->query($contactsQuery);

                while ($contactRow = $contactsResult->fetch_assoc()) {
                    $selectedContact = ($contactRow['ID'] == $contactID) ? 'selected' : '';
                    echo "<option value=\"{$contactRow['ID']}\" $selectedContact>{$contactRow['FirstName']} {$contactRow['LastName']}, {$contactRow['Position']}</option>";
                }
                ?>
            </select><br>
        </div>

        <div class="single-line-input">
            <label for="photo">Upload Photo:</label>
            <input type="file" name="photo" accept="image/*"><br>
        </div>


        <input type="submit" value="Update Job">
    </form>
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>
