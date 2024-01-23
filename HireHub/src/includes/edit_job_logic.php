<?php
session_start();

include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobID = $_POST['jobID'];
    $position = $_POST["position"];
    $jobType = $_POST["jobType"];
    $salary = $_POST["salary"];
    $experience = $_POST["experience"];
    $contactID = $_POST["contact"];

    $updateSql = "UPDATE Job SET 
                  Position = '$position', 
                  JobType = '$jobType', 
                  Salary = '$salary', 
                  MinExperience = $experience, 
                  ContactID = $contactID 
                  WHERE JobID = $jobID";

    // If a new photo is uploaded, update the photo as well
    if (!empty($_FILES["photo"]["name"])) {
        // Upload new photo
        $photoName = $_FILES["photo"]["name"];
        $photoTmpName = $_FILES["photo"]["tmp_name"];

        // Change the name of the file to JobID
        $originalFileExtension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $newFilename = "../media/position_cover/{$jobID}.{$originalFileExtension}";

        // Move the file to a "position_cover" folder
        move_uploaded_file($photoTmpName, $newFilename);

        // Update the job with the new photo
        $updatePhotoSql = "UPDATE Job SET Photo = '{$jobID}.{$originalFileExtension}' WHERE JobID = $jobID";
        $conn->query($updatePhotoSql);
    }

    if ($conn->query($updateSql) === TRUE) {
        // If the update is successful, redirect to job_details.php with the updated job ID
        header("Location: ../pages/job_details.php?job_id=" . $jobID);
        exit();
    } else {
        echo "Error updating job: " . $updateSql . "<br>" . $conn->error;
    }

} else {
    echo "Invalid request.";
}

$conn->close();
?>
