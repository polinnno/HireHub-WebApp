<?php
global $conn;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$recruiterID = $_SESSION['RecruiterID'] ?? 0;


// Check if the job_id is set
if (isset($_GET['job_id'])) {
    $jobID = $_GET['job_id'];

    include '../includes/db_connection.php';

    $candidatesQuery = "
        SELECT c.CandidateID, c.FirstName, c.LastName, c.Email, c.City, ja.ApplicationDateTime
        FROM Candidate c
        JOIN Application ja ON c.CandidateID = ja.CandidateID
        WHERE ja.JobID = $jobID
    ";

    $candidatesResult = $conn->query($candidatesQuery);
} else {
    // Redirect to jobs.php if job_id is not set
    header('Location: jobs.php');
    exit();
}

function getInterviewsForCandidateAndJob($candidateID, $jobID) {
    $sql = "SELECT * FROM Interview WHERE CandidateID = $candidateID AND JobID = $jobID";

    global $conn;
    $result = $conn->query($sql);

    return $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/applications.css"> <!-- Adjust the path as needed -->
    <title>Applied Candidates List</title>
</head>
<body>

<h2>Applied Candidates List</h2>


<?php if ($candidatesResult->num_rows > 0): ?>


    <div id="search-container">
        <label for="search">Search:</label>
        <input type="text" id="search" oninput="filterCandidates()">
    </div>

<div id="applicants-table-container">
    <table>
        <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>City</th>
            <th>Applied on</th>
            <th>Location</th>
            <th>Interview Status</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($candidate = $candidatesResult->fetch_assoc()): ?>
            <?php
            // Assume $interviewResult is the result set of interviews for the current candidate
            // You may need to adjust the query accordingly
            $interviewResult = getInterviewsForCandidateAndJob($candidate['CandidateID'], $jobID); // Replace $currentJobID with the appropriate value
            $interview = $interviewResult->fetch_assoc();
            ?>
            <tr>
                <td><?= $candidate['FirstName']; ?></td>
                <td><?= $candidate['LastName']; ?></td>
                <td><?= $candidate['Email']; ?></td>
                <td><?= $candidate['City']; ?></td>

                <td><?= $candidate['ApplicationDateTime']; ?></td>
                <td>
                    <?php if ($interview): ?>
                        <?= $interview['Location']; ?>
                    <?php else: ?>
                        <label for="location-interview-<?= $candidate['CandidateID']; ?>">Schedule an interview:</label>
                        <input type="text" id="location-interview-<?= $candidate['CandidateID']; ?>"
                               name="location-interview" placeholder="Location">
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($interview): ?>
                        Scheduled on <?= (new DateTime($interview['InterviewDateTime']))->format('l, M jS, Y G:i'); ?>
                    <?php else: ?>
                        <label for="schedule-interview-<?= $candidate['CandidateID']; ?>">Schedule an interview:</label>
                        <input type="datetime-local" id="schedule-interview-<?= $candidate['CandidateID']; ?>" name="interviewDateTime">
                        <button onclick="scheduleInterview(<?= $candidate['CandidateID']; ?>)">Schedule</button>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
    <p id="notion">No candidates have applied for this job.</p>
<?php endif; ?>

<script>

    function filterCandidates() {
        const searchInput = document.getElementById('search').value.toLowerCase();
        const rows = document.querySelectorAll('#applicants-table-container tbody tr');

        rows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            row.style.display = rowText.includes(searchInput) ? '' : 'none';
        });
    }


    function scheduleInterview(candidateID) {
        // Get the selected date and time from the input field
        const interviewDateTime = document.getElementById(`schedule-interview-${candidateID}`).value;
        const interviewLocation = document.getElementById(`location-interview-${candidateID}`).value;

        // Check if both date/time and location are entered
        if (interviewDateTime && interviewLocation) {
            // Make an AJAX request to schedule the interview
            const formData = new FormData();
            formData.append('scheduleInterview', true);
            formData.append('candidateID', candidateID);
            formData.append('interviewDateTime', interviewDateTime);
            formData.append('interviewLocation', interviewLocation); // Add location to the formData

            formData.append('jobID', <?= $jobID; ?>); // Replace with the actual job ID

            // Add other necessary data to the formData if needed

            fetch('../includes/interview_functions.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        // Reload the page after scheduling the interview
                        location.reload();
                    } else {
                        alert('Error scheduling the interview.');
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            alert('Please enter both date/time and location before scheduling the interview.');
        }
    }

</script>
</body>
</html>
