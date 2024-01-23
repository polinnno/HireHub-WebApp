<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';
include '../includes/candidate_functions.php';
include '../includes/job_functions.php';


$candidateID = $_SESSION['CandidateID'] ?? 0;

if (!$candidateID) {
    header("Location: ../pages/login.php");
    exit();
}

$candidateID = $_SESSION['CandidateID'] ?? 0;
$candidateDetails = getCandidateDetails($candidateID);
$appliedJobs = getJobsAppliedByCandidate($candidateID);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<body>
<div id="main-info">
    <div class="user-info">
        <h2 id="tit"><?= $candidateDetails['FirstName'] . ' ' . $candidateDetails['LastName']; ?></h2>
        <p><strong>Username:</strong> <?= $candidateDetails['Username']; ?></p>
        <p><strong>Gender:</strong> <?= $candidateDetails['Gender']; ?></p>
        <p><strong>Year of Birth:</strong> <?= $candidateDetails['YearOfBirth']; ?></p>
        <p><strong>Email:</strong> <?= $candidateDetails['Email']; ?></p>
        <p><strong>City:</strong> <?= $candidateDetails['City']; ?></p>
        <p><strong>Address:</strong> <?= $candidateDetails['Address']; ?></p>
        <p><a id="favorites-link" href="../pages/favorites.php">Go to Favorites</a></p>
        <a id="favorites-link" href="#interview-container">Jump to Interviews</a>
        <br><br>
        <div class="logout-container">
            <form action="../includes/logout.php" method="post">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>
    <div id="pp-container">
        <img id="pp" src="../media/pps-candidate/<?= $candidateDetails['ProfilePicture'] ?? $defaultProfilePicture; ?>"
             alt="<?= $candidateDetails['FirstName']; ?> profile picture ">
    </div>
</div>

<div class="applied-jobs-container">
    <h2>Jobs Applied</h2>
    <div class="job-container">
        <?php
        $jobsPerRow = 3;
        $jobsCount = count($appliedJobs);

        for ($i = 0; $i < $jobsCount; $i += $jobsPerRow):
            echo '<div class="job-row">';
            for ($j = $i; $j < $i + $jobsPerRow && $j < $jobsCount; $j++):
                $job = $appliedJobs[$j];
                $isFavorite = isJobInFavorites($candidateID, $job['JobID']);

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

                        <a href="#" onclick="withdrawApplication(<?= $job['JobID']; ?>)">Withdraw Application</a>

                        <div class="heart-icon <?= $isFavorite ? 'filled' : ''; ?>" data-job-id="<?= $job['JobID']; ?>"
                             onclick="toggleFavorite(this, <?= $candidateID ?>, <?= $job['JobID'] ?>)">
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

<div id="interview-container">
    <?php include '../includes/candidate-interviews.php'; ?>
</div>

<script>
    window.onload = function () {
        // Update favorite status for each job
        <?php foreach ($appliedJobs as $job): ?>
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
                    // Handle errors
                    console.error('Withdrawal failed');
                }
            })
            .catch(error => console.error('Error:', error));
    }

</script>
</body>
</html>
