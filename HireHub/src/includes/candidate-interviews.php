<?php
include '../includes/db_connection.php';

$candidateID = $_SESSION['CandidateID'] ?? 0;


function getInterviewsForCandidate($candidateID) {
    global $conn;

    $interviewsQuery = "
        SELECT 
            i.InterviewDateTime, 
            r.Name AS RecruiterName, 
            j.Position AS JobPosition, 
            j.JobID,
            c.FirstName AS ContactFirstName,
            c.LastName AS ContactLastName,
            c.PhoneNumber AS ContactPhoneNumber,
            c.Email AS ContactEmail,
            c.Position AS ContactPosition
        FROM Interview i
        JOIN Job j ON i.JobID = j.JobID
        JOIN Recruiter r ON j.RecruiterID = r.RecruiterID
        JOIN Contact c ON j.ContactID = c.ID
        WHERE i.CandidateID = $candidateID
        ORDER BY i.InterviewDateTime DESC";

    return $conn->query($interviewsQuery);
}


$interviewsResult = getInterviewsForCandidate($candidateID);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/candidate-interviews.css">
    <title>Interviews</title>
</head>
<body>

<h2>Interviews</h2>

<?php if ($interviewsResult->num_rows > 0): ?>
    <div id="interviews-table-container">
        <table>
            <thead>
            <tr>
                <th>Recruiter Name</th>
                <th>Job Position</th>
                <th>Interview Date and Time</th>
                <th>Contact</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($interview = $interviewsResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $interview['RecruiterName']; ?></td>
                    <td>
                        <a href="../pages/job_details.php?job_id=<?= $interview['JobID']; ?>">
                            <?= $interview['JobPosition']; ?>
                        </a>
                    </td>
                    <td><?= (new DateTime($interview['InterviewDateTime']))->format('l, M jS, Y G:i'); ?></td>
                    <td>
                        <strong><?= $interview['ContactFirstName'] . ' ' . $interview['ContactLastName']; ?></strong><br>
                        <?= $interview['ContactPhoneNumber']; ?>,
                        <?= $interview['ContactEmail']; ?><br>
                        <?= $interview['ContactPosition']; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p id="notion">No interviews scheduled yet.</p>
<?php endif; ?>

</body>
</html>
