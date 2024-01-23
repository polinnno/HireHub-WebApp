<?php
include '../includes/header.php';
include '../includes/DB_connection.php';

function getHomePageJobs() {
    global $conn;

    $sql = "SELECT * FROM job ORDER BY PostingDateTime DESC LIMIT 3";
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error: " . $conn->error;
        exit();
    }

    $jobs = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobs[] = $row;
        }
    }

    return $jobs;
}

$homePageJobs = getHomePageJobs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/home.css">
    <title>Home</title>
</head>
<body>
<div id="main-banner">

    <div id="banner-part">
        <p>
            At HireHub, we believe in more than <br>
            just filling positions – we're dedicated to forging <br>
            lasting connections between exceptional
            talent and innovative companies. <br>
            <br>
            Join us on a journey where career aspirations <br>
            meet their perfect opportunities.
        </p>
    </div>
    <div id="logo">
        <p class="home-slogan" id="home-company-name">HireHub</p>
        <p class="home-slogan">Your Gateway to Professional Success.</p>
    </div>
</div>

<div class="job-container">
    <h2>Latest Job Announcements</h2>

    <?php
    $jobsPerRow = 3;
    $jobsCount = count($homePageJobs);

    for ($i = 0; $i < $jobsCount; $i += $jobsPerRow):
        echo '<div class="job-row">';
        for ($j = $i; $j < $i + $jobsPerRow && $j < $jobsCount; $j++):
            $job = $homePageJobs[$j]; ?>
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
                </div>
            </div>
        <?php
        endfor;
        echo '</div>';
    endfor;
    ?>
    <a id="jobs-forward" href="jobs.php">See more</a>
</div>

<?php include '../includes/footer.html'; ?>
</body>
</html>
