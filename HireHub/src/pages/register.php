<?php
session_start();

global $conn;
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../includes/db_connection.php';
include '../includes/validation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["candidateEmail"] ?? '';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validation checks
    if (!isValidUsername($username)) {
        echo "Invalid username format. Please use only alphanumeric characters and underscores.";
    } elseif (!isValidPassword($password)) {
        echo "Invalid password. It must be at least 6 characters long.";
    } elseif (!isEmailUnique($email)) {
        echo "Please use another email.";
    } elseif (!isUsernameUnique($username)) {
        echo "Username is already taken. Please choose another username.";
    } else {
        // Candidate fields
        $firstName = $_POST["candidateFirstName"] ?? '';
        $lastName = $_POST["candidateLastName"] ?? '';
        $gender = $_POST["gender"] ?? '';
        $birthYear = $_POST["candidateBirthYear"] ?? '';
        $city = $_POST["candidateCity"] ?? '';
        $address = $_POST["candidateAddress"] ?? '';

        $sql = "INSERT INTO Candidate (Username, HashedPassword, Email, FirstName, LastName, Gender, YearOfBirth, City, Address)
                VALUES ('$username', '$hashedPassword', '$email', '$firstName', '$lastName', '$gender', $birthYear, '$city', '$address')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $username;

            echo "Record inserted successfully for Candidate";
            header('Location: home.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/register.css">

    <title>Register</title>
</head>

<body>
<p id="login-link">Already have an account? <a href="login.php">Log in</a></p>

<h1>Register</h1>
<div class="form-container">


<form action="register.php" method="post">
    <div class="single-line-input">
        <label for="username">Username:</label>
        <input type="text" name="username" pattern="^[a-zA-Z0-9_]{6,}$" title="Please use only alphanumeric characters and underscores." required><br>
    </div>

    <div class="single-line-input">
        <label for="password">Password:</label>
        <input type="password" name="password" pattern="^(?=.*\d)[a-zA-Z\d]{6,}$" title="Password must be at least 6 characters long and can contain letters and numbers" required><br>
    </div>

    <div class="single-line-input">
        <label for="candidateEmail">Email:</label>
        <input type="email" name="candidateEmail" required><br>
    </div>

    <div class="two-column-input">
        <div class="column">
            <label for="candidateFirstName">First Name:</label>
            <input type="text" name="candidateFirstName" pattern="[A-Za-z]+" title="Please enter one word (letters only)" required><br>
        </div>
        <div class="column">
            <label for="candidateLastName">Last Name:</label>
            <input type="text" name="candidateLastName" pattern="[A-Za-z]+" title="Please enter one word (letters only)" required><br>
        </div>
    </div>
    <div class="three-column-input">
        <div class="column">
            <label for="candidateCity">City:</label>
            <input type="text" name="candidateCity"><br>
        </div>
        <div class="column">
            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="-" selected>-</option>
                <option value="female">Female</option>
                <option value="male">Male</option>
                <option value="other">Other</option>
            </select><br>
        </div>
        <div class="column">
            <label for="candidateBirthYear">Year of Birth:</label>
            <input type="number" name="candidateBirthYear" min="1900" max="<?php echo date('Y'); ?>" required><br>
        </div>


    </div>
    <div class="single-line-input">
        <label for="candidateAddress">Address:</label>
        <input type="text" name="candidateAddress"><br>
    </div>



    <input type="submit" value="Submit">
</form>

<p id="forward">Looking to hire? <a href="register_recruiter.php">Register as a Recruiter</a></p>
</div>
</body>
</html>
