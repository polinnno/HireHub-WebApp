<?php
session_start();

include '../includes/db_connection.php';
include '../includes/validation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check Candidate table
    $candidateSql = "SELECT * FROM Candidate WHERE Username='$username'";
    $candidateResult = $conn->query($candidateSql);

    // Check Recruiter table
    $recruiterSql = "SELECT * FROM Recruiter WHERE Username='$username'";
    $recruiterResult = $conn->query($recruiterSql);

    if ($candidateResult->num_rows > 0) {
        $candidateData = $candidateResult->fetch_assoc();
        if (password_verify($password, $candidateData['HashedPassword'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'candidate';
            $_SESSION['CandidateID'] = $candidateData['CandidateID'];


            header("Location: jobs.php");
            exit();
        }
    } elseif ($recruiterResult->num_rows > 0) {
        $recruiterData = $recruiterResult->fetch_assoc();

        if (password_verify($password, $recruiterData['HashedPassword'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'recruiter';
            $_SESSION['RecruiterID'] = $recruiterData['RecruiterID'];

            header("Location: jobs.php");
            exit();
        }
    } else {
        error_log("somethings off");
        $error_message = "Invalid username or password. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/login.css">

    <title>Login</title>
</head>

<body>
<h1>Login</h1>
<div class="form-container">
    <form action="login.php" method="post">
        <div class="single-line-input">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
        </div>

        <div class="single-line-input">
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
        </div>
        <?php if (isset($error_message)) : ?>
            <br>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <input type="submit" value="Login">
    </form>

    <p id="forward">Don't have an account? <a href="register.php">Register as Candidate</a> or <a href="register_recruiter.php">Register as Recruiter</a></p>
</div>
</body>
</html>
