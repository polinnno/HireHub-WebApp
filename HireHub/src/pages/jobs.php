<?php
session_start();

global $candidateID;
$candidateID = $_SESSION['CandidateID'] ?? 0;

error_log($candidateID);

include '../includes/header.php';
include '../includes/db_connection.php';
include '../includes/job_functions.php';

$sql = "SELECT * FROM Job";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $jobs = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $jobs = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/jobs.css">
    <title>Jobs</title>
</head>
<body>
<h2>Available jobs:</h2>

<div class="job-container">
    <?php
    $jobsPerRow = 3;
    $jobsCount = count($jobs);

    for ($i = 0; $i < $jobsCount; $i += $jobsPerRow):
        echo '<div class="job-row">';
        for ($j = $i; $j < $i + $jobsPerRow && $j < $jobsCount; $j++):
            $job = $jobs[$j];
            $isFavorite = isJobInFavorites($candidateID, $job['JobID']);

            ?>
            <div class="job-card">
                <div class="cover-container">
                    <img src="../media/position_cover/<?= $job['Photo']??"default.png"; ?>"
                         alt="<?= $job['Position']; ?> Photo">
                </div>

                <div class="info-container">

                    <h3>
                        <a href="job_details.php?job_id=<?= $job['JobID']; ?>">
                            <?= $job['Position']; ?>
                        </a>
                    </h3>

                    <p><strong>Job Type:</strong> <?= $job['JobType']; ?></p>
                    <p><strong>Salary: </strong>€ <?= $job['Salary']; ?></p>
                    <p><strong>Minimum Experience:</strong> <?= $job['MinExperience']; ?> years</p>

                    <?php if (!isset($_SESSION['RecruiterID'])) { ?>
                        <a href="job_details.php?job_id=<?= $job['JobID']; ?>" >Apply</a>

                        <div class="heart-icon <?= $isFavorite ? 'filled' : ''; ?>" data-job-id="<?= $job['JobID']; ?>"
                             onclick="toggleFavorite(this, <?= $candidateID ?>, <?= $job['JobID'] ?>)">
                            <span class="heart">&#9825;</span>
                        </div>
                    <?php } ?>

                </div>
            </div>
        <?php
        endfor;
        echo '</div>';
    endfor;
    ?>
</div>
<?php include '../includes/footer.html'; ?>

<script>
    window.onload = function () {
        <?php foreach ($jobs as $job): ?>
        fetchFavoriteStatus(<?= $candidateID ?>, <?= $job['JobID'] ?>, <?= isJobInFavorites($candidateID, $job['JobID']) ? 'true' : 'false' ?>);

        <?php endforeach; ?>

        function fetchFavoriteStatus(candidateID, jobID, isFavorite) {
            const heartIcon = document.querySelector(`.heart-icon[data-job-id="${jobID}"] .heart`);

            if (heartIcon) {
                if (isFavorite) {
                    heartIcon.classList.add('filled');
                } else {
                    heartIcon.classList.remove('filled');
                }
            } else {
                console.error(`Heart icon not found for job ID ${jobID}`);
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {

    });


    function toggleFavorite(icon, candidateID, jobID) {

        <?php if (isset($_SESSION['RecruiterID'])) {
            exit;
        }?>


        // If the user is guest - suggest to log in.
        <?php if (!isset($_SESSION['CandidateID'])) { ?>
            if (!confirm("Log in to add to Favorites.")) {
                return;
            } else {
                <?php header('Location: login.php'); ?>
            }
        <?php } ?>

        // User is Candidate -- add to Favorites.
        const heartIcon = icon.querySelector('.heart');
        const formData = new FormData();
        formData.append('candidateID', candidateID);
        formData.append('jobID', jobID);

        fetch('../includes/job_functions.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(result => {
                if (result === 'added') {
                    heartIcon.classList.add('filled');
                } else if (result === 'removed') {
                    heartIcon.classList.remove('filled');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function applyForJob(jobID) {
        const formData = new FormData();
        formData.append('candidateID', <?= $candidateID ?>);
        formData.append('jobID', jobID);

        fetch('../includes/apply.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(result => {
                if (result === 'applied') {
                    alert('Application successful!');
                } else if (result === 'already_applied') {
                    alert('You have already applied for this job.');
                } else {
                    alert('Error applying for the job.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

</script>
</body>

</html>
