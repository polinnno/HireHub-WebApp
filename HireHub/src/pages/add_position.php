<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

$recruiterID = $_SESSION['RecruiterID'] ?? 0;
include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position = $_POST["position"];
    $jobType = $_POST["jobType"];
    $salary = $_POST["salary"];
    $experience = $_POST["experience"];
    $contactID = $_POST["contact"];

    // Upload photo
    $photoName = $_FILES["photo"]["name"];
    $photoTmpName = $_FILES["photo"]["tmp_name"];


    // Insert data into Job table
    $sql = "INSERT INTO Job (RecruiterID, Position, JobType, Salary, MinExperience, ContactID, PostingDateTime, Photo)
            VALUES ($recruiterID, '$position', '$jobType', '$salary', $experience, $contactID, NOW(), '')";

    // Execute SQL query
    if ($conn->query($sql) === TRUE) {
        // Get the auto-incremented ID assigned to the job
        $jobID = $conn->insert_id;

        // Change the name of the photo file to JobID.
        $originalFileExtension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $newFilename = "../media/position_cover/{$jobID}.{$originalFileExtension}";

        // Move the file to a "position_cover" folder.
        move_uploaded_file($photoTmpName, $newFilename);

        // Update the job with the new photo file name.
        $updateSql = "UPDATE Job SET Photo = '{$jobID}.{$originalFileExtension}' WHERE JobID = $jobID";
        $conn->query($updateSql);

        header('Location: jobs.php');
        echo "Job added successfully.";
    }  else {
        echo "Error adding job: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/add_position.css">
    <title>Add Job</title>
</head>
<body>
<button id="back" onclick="goBack()">&#x2190; back</button>

<h1>Add Job</h1>
<div class="form-container">
    <form action="add_position.php" method="post" enctype="multipart/form-data">
        <div class="single-line-input">
            <label for="position">Position:</label>
            <input type="text" name="position" required><br>
        </div>
        <div class="single-line-input">
            <label for="jobType">Job Type:</label>
            <select name="jobType">
                <?php
                include '../includes/db_connection.php';

                // Get job types from the database.
                $jobTypesQuery = "SELECT * FROM JobTypes";
                $jobTypesResult = $conn->query($jobTypesQuery);

                // Display job types as options in the dropdown.
                while ($jobType = $jobTypesResult->fetch_assoc()) {
                    echo "<option value=\"{$jobType['JobType']}\">{$jobType['JobType']}</option>";
                }
                ?>
            </select><br>
        </div>

        <div class="two-column-input">
            <div class="column">
                <label for="salary">Salary (€):</label>
                <input type="number" name="salary" required><br>
            </div>
            <div class="column">
                <label for="experience">Minimum Years of Experience:</label>
                <input type="number" name="experience" min="0" required><br>
            </div>
        </div>

        <div class="single-line-input">
            <label for="contact">Select Contact:</label>
            <select name="contact" required>
                <?php
                // Get contacts for the current recruiter.
                $contactsQuery = "SELECT ID, FirstName, LastName, Position FROM Contact WHERE RecruiterID = $recruiterID";
                $contactsResult = $conn->query($contactsQuery);

                while ($contact = $contactsResult->fetch_assoc()) {
                    echo "<option value=\"{$contact['ID']}\">{$contact['FirstName']} {$contact['LastName']}, {$contact['Position']}</option>";
                }
                ?>
            </select><br>
        </div>

        <div class="single-line-input">
            <label for="photo">Upload Photo:</label>
            <input type="file" name="photo" accept="image/*"><br>
        </div>

        <input type="submit" value="Add Job">
    </form>
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>
