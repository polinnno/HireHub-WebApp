<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//TODO: add an arrow back!
include '../includes/header.php';
include '../includes/db_connection.php';
include '../includes/candidate_functions.php';
include '../includes/job_functions.php';


$candidateID = $_SESSION['CandidateID'] ?? 0;

if (!$candidateID) {
    header("Location: ../pages/login.php");
    exit();
}

$candidateDetails = getCandidateDetails($candidateID);
$defaultProfilePicture = "default.jpeg";

$favoritedJobs = getFavoritedJobs($candidateID);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/account.css">
    <title>Favorites</title>
</head>
<body>
<a id="back" href="account.php">&#8592; back</a>

<h2 id="favs-tit"><?= $candidateDetails['Username']; ?>'s favorite jobs:</h2>

<div id="main-info">
    <div class="user-info">
    </div>

</div>

<div class="favorite-jobs-container">
    <div class="job-container">
        <?php
        $jobsPerRow = 3;
        $jobsCount = count($favoritedJobs);

        for ($i = 0; $i < $jobsCount; $i += $jobsPerRow):
            echo '<div class="job-row">';
            for ($j = $i; $j < $i + $jobsPerRow && $j < $jobsCount; $j++):
                $job = $favoritedJobs[$j];
                $isFavorite = true; // All jobs on this page are favorite.

                ?>
                <div class="job-card">
                    <div class="cover-container">
                        <img src="../media/position_cover/<?= $job['Photo']; ?>" alt="<?= $job['Position']; ?> Photo">
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

                        <div class="heart-icon <?= $isFavorite ? 'filled' : ''; ?>" data-job-id="<?= $job['JobID']; ?>" onclick="toggleFavorite(this, <?= $candidateID ?>, <?= $job['JobID'] ?>)">
                            <span class="heart">&#9825;</span>
                        </div>
                    </div>
                </div>
            <?php
            endfor;
            echo '</div>';
        endfor;
        ?>
    </div>
</div>

<?php include '../includes/footer.html'; ?>

<script>
    window.onload = function () {
        //  Update favorite status for each job.
        <?php foreach ($favoritedJobs as $job): ?>
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

</script>

</body>
</html>
