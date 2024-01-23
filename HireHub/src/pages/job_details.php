<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../includes/header.php';
include '../includes/job_functions.php';

// Get the job details from the database based on the job ID passed through URL
if (isset($_GET['job_id'])) {
    $jobID = $_GET['job_id'];

    error_log("Job ID: {$jobID}");
    include '../includes/db_connection.php';

    $jobDetailsQuery = "
        SELECT j.*, r.Name
        FROM Job j
        JOIN Recruiter r ON j.RecruiterID = r.RecruiterID
        WHERE j.JobID = $jobID";

    $jobDetailsResult = $conn->query($jobDetailsQuery);

    if ($jobDetailsResult->num_rows > 0) {
        $jobDetails = $jobDetailsResult->fetch_assoc();
    } else {
        header('Location: 404.php');
    }

    // Check the user's role (CandidateID or RecruiterID)
    $candidateID = $_SESSION['CandidateID'] ?? 0;
    $recruiterID = $_SESSION['RecruiterID'] ?? 0;

    error_log($candidateID);
    error_log($recruiterID);

    // Check if the user has applied for this job (if a candidate is logged in)
    $appliedForJob = ($candidateID && isJobApplied($candidateID, $jobID));

    // Check if the user is the recruiter who posted this job
    $postedByRecruiter = ($recruiterID && $jobDetails['RecruiterID'] == $recruiterID);

} else { header('Location: jobs.php'); }


function isJobApplied($candidateID, $jobID): bool {
    global $conn;
    include '../includes/db_connection.php';

    $applicationQuery = "SELECT * FROM Application 
                         WHERE CandidateID = $candidateID AND JobID = $jobID";

    $applicationResult = $conn->query($applicationQuery);
    $conn->close();

    return $applicationResult->num_rows > 0;
}

function getApplicationDateTime($candidateID, $jobID) {
    global $conn;
    include '../includes/db_connection.php';

    $applicationQuery = "SELECT ApplicationDateTime FROM Application 
                         WHERE CandidateID = $candidateID AND JobID = $jobID";
    $applicationResult = $conn->query($applicationQuery);

    if ($applicationResult->num_rows > 0) {
        $application = $applicationResult->fetch_assoc();
        $rawDateTime = $application['ApplicationDateTime'];
        $formattedDateTime = date("Y-m-d H:i:s", strtotime($rawDateTime));

        // Format the date to a better format.
        $formattedDateTime = date("Y-m-d H:i:s", strtotime($formattedDateTime));
        return date("F j, Y H:i", strtotime($formattedDateTime));
    }

    $conn->close();
    return null; // No application found -- return null.
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../styles/job_details.css">
        <title><?= $jobDetails['Position']; ?></title>
    </head>
    <body>
    <button id="back" onclick="goBack()">&#x2190; back</button>

    <div class="job-details-container">

        <div class="cover-container">
            <img src="../media/position_cover/<?= $jobDetails['Photo']; ?>" alt="<?= $jobDetails['Position']; ?> Photo">
        </div>

        <div class="info-container">
            <h3><?= $jobDetails['Position']; ?></h3>

            <p><strong>Job Type:</strong> <?= $jobDetails['JobType']; ?></p>
            <p><strong>Salary: </strong>€ <?= $jobDetails['Salary']; ?></p>
            <p><strong>Minimum Experience:</strong> <?= $jobDetails['MinExperience']; ?> years</p>
            <p><?= is_null($jobDetails['Description']) ? "" : $jobDetails['Description'] ?></p>
            <p><strong>Posted by:</strong> <?= $jobDetails['Name']; ?></p>

            <?php if ($candidateID): ?>
                <p id="extra-space"></p>

                <?php if ($appliedForJob): ?>
                    <p id="notion">You've applied for this job on <?= getApplicationDateTime($candidateID, $jobID); ?>
                        .</p>
                    <button onclick="withdrawApplication(<?= $jobID ?>)">Withdraw Application</button>
                <?php else: ?>
                    <button onclick="applyForJob(<?= $jobID ?>)">Apply</button>
                <?php endif; ?>

            <?php elseif ($recruiterID): ?>
                <?php if ($postedByRecruiter): ?>
                    <div class="action-buttons">
                        <a id="edit-btn" href="edit_job.php?job_id=<?= $jobID ?>">✏️ Edit</a>
                        <button id="delete-btn" onclick="deleteJob(<?= $jobID ?>)">Delete</button>

                    </div>
                <?php endif; ?>

            <?php else: ?>
                <p id="forward"><a href="login.php">Log in to apply</a></p>
            <?php endif; ?>
        </div>
    </div>
    <div id="applications container">
        <?php if ($postedByRecruiter) {
            $_GET['job_id'] = $jobID;
            include '../includes/applications.php';
        }?>
    </div>
    <?php include '../includes/footer.html'; ?>

    <script>
        function applyForJob(jobID) {
            const formData = new FormData();
            formData.append('candidateID', <?= $candidateID ?>);
            formData.append('jobID', jobID);
            formData.append('applyForJob', true);

            fetch('../includes/job_functions.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'added') {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function withdrawApplication(jobID) {
            const formData = new FormData();
            formData.append('candidateID', <?= $candidateID ?>);
            formData.append('jobID', jobID);
            formData.append('withdrawApplication', true);

            fetch('../includes/job_functions.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'withdrawn') {
                        location.reload();
                    } else {
                        // Handle errors.
                        console.error('Withdrawal failed');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function editJob(jobID) {
            // Redirect to edit_job.php
            window.location.href = 'edit_job.php?job_id=' + jobID;
        }

        function deleteJob(jobID) {
            if (!confirm("Are you sure you want to delete this job posting?")) {
                return;
            }

            const formData = new FormData();
            formData.append('deleteJob', true);
            formData.append('jobID', jobID);
            formData.append('recruiterID', <?= $recruiterID ?>);

            fetch('../includes/recruiter_functions.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'deleted') {
                        alert('Job deleted successfully!');
                        const currentPage = window.location.pathname;

                        if (currentPage.includes('job_details.php')) {
                            // Redirect to jobs.php if on job_details.php.
                            window.location.href = 'jobs.php';
                        } else {
                            // Reload the current page for other pages.
                            location.reload();
                        }                    } else {
                        alert('Error deleting the job.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }


        function goBack() {
            window.history.back();
        }

    </script>
    </body>
</html>