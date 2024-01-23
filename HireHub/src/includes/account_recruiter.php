<?php
global $defaultProfilePicture;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../includes/recruiter_functions.php';

$recruiterID = $_SESSION['RecruiterID'] ?? 0;


if (!$recruiterID) {
    header("Location: ../pages/login.php");
    exit();
}

// Fetch recruiter details
$recruiterDetails = getRecruiterDetails($recruiterID);

// Fetch recruiter contacts
$recruiterContacts = getRecruiterContacts($recruiterID);

// Fetch jobs posted by the recruiter
$recruiterJobs = getRecruiterJobs($recruiterID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/account.css">
    <title>Recruiter Account</title>
</head>
<body>
<div id="main-info">
    <div class="user-info">
        <h2 id="tit"><?= $recruiterDetails['Name']; ?></h2>
        <p><strong>Username:</strong> <?= $recruiterDetails['Username']; ?></p>
        <p><strong>City:</strong> <?= $recruiterDetails['City']; ?></p>
        <p><strong>Owner Name:</strong> <?= $recruiterDetails['OwnerFirstName'] . ' ' . $recruiterDetails['OwnerLastName']; ?></p>
        <p><strong>Owner Address:</strong> <?= $recruiterDetails['OwnerAddress']; ?></p>
        <p><strong>Email:</strong> <?= $recruiterDetails['Email']; ?></p>
        <div class="logout-container">
            <form action="../includes/logout.php" method="post">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>
    <div id="pp-container">
        <img id="pp" src="../media/pps-recruiter/<?= $recruiterDetails['ProfilePicture'] ?? $defaultProfilePicture; ?>"
             alt="<?= $recruiterDetails['Name']; ?> profile picture ">
    </div>
</div>
<div class="contacts-container">
    <h2>Contacts</h2>

    <div class="contact-row">
        <?php $count = 0; ?>
        <?php foreach ($recruiterContacts as $contact): ?>
        <div class="contact-card">
            <p><strong><?= $contact['FirstName'] . ' ' . $contact['LastName']; ?></strong></p>
            <p><?= $contact['PhoneNumber']; ?></p>
            <p><?= $contact['Email']; ?></p>
            <p><?= $contact['Position']; ?></p>
            <?php
            $jobPostingCount = getJobPostingCountForContact($contact['ID']);
            if ($jobPostingCount > 0): ?>
                <p class="contact-use-notification"><strong><?= $jobPostingCount ?></strong> job postings use this contact</p>
            <?php else: ?>
                <span class="trash-icon" onclick="deleteContact(<?= $contact['ID']; ?>)">&#128465;</span>
            <?php endif; ?>
        </div>
        <?php $count++; ?>
        <?php if ($count % 4 == 0): ?>

    </div><div class="contact-row">
        <?php endif; ?>
        <?php endforeach; ?>
        <a href="../pages/add_contact.php" class="contact-card" id="add-contact-btn">Add Contact
            <p id="plus">
                &plus;
            </p>
        </a>

    </div>
</div>


<h2>Jobs you posted</h2>
<a href="../pages/add_position.php" id="add-position-btn">Add Job</a>
<div class="job-container">
    <?php
    $jobsPerRow = 3;
    $jobsCount = count($recruiterJobs);

    for ($i = 0; $i < $jobsCount; $i += $jobsPerRow):
        echo '<div class="job-row">';
        for ($j = $i; $j < $i + $jobsPerRow && $j < $jobsCount; $j++):
            $job = $recruiterJobs[$j]; ?>
            <div class="job-card">
                <div class="cover-container">
                    <img src="../media/position_cover/<?= $job['Photo']; ?>" alt="<?= $job['Position']; ?> Photo">
                </div>

                <div class="info-container">
                    <h3>
                        <a href="../pages/job_details.php?job_id=<?= $job['JobID']; ?>">
                            <?= $job['Position']; ?>
                        </a>
                    </h3>
                    <p><strong>Job Type:</strong> <?= $job['JobType']; ?></p>
                    <p><strong>Salary: </strong>€ <?= $job['Salary']; ?></p>
                    <p><strong>Minimum Experience:</strong> <?= $job['MinExperience']; ?> years</p>

                    <div class="action-buttons">
                        <a id="edit-btn" href="../pages/edit_job.php?job_id=<?= $job['JobID']; ?>">✏️ Edit</a>
                        <a href="#" id="delete-btn" onclick="deleteJob(<?= $job['JobID']; ?>)">Delete</a>
                    </div>
                </div>
            </div>

        <?php
        endfor;
        echo '</div>';
    endfor;
    ?>
</div>
<script>
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
                    location.reload();
                } else {
                    alert('Error deleting the job.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function deleteContact(contactID) {
        if (!confirm("Are you sure you want to delete this contact?")) {
            return;
        }

        const formData = new FormData();
        formData.append('deleteContact', true);
        formData.append('contactID', contactID);
        formData.append('recruiterID', <?= $recruiterID ?>);

        fetch('../includes/recruiter_functions.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(result => {
                if (result === 'deleted') {
                    location.reload();
                } else {
                    alert('Error.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

</script>
</body>
</html>
