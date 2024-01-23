<?php
session_start();

global $conn;

include '../includes/db_connection.php';
include '../includes/validation.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["recruiterEmail"] ?? '';

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
        // Get info from recruiter fields.
        $name = $_POST["recruiterName"] ?? '';
        $city = $_POST["recruiterCity"] ?? '';
        $ownerFirstName = $_POST["ownerFirstName"] ?? '';
        $ownerLastName = $_POST["ownerLastName"] ?? '';
        $ownerAddress = $_POST["ownerAddress"] ?? '';

        $sql = "INSERT INTO Recruiter (Username, HashedPassword, Email, Name, City, OwnerFirstName, OwnerLastName, OwnerAddress)
            VALUES ('$username', '$hashedPassword', '$email', '$name', '$city', '$ownerFirstName', '$ownerLastName', '$ownerAddress')";

        if ($conn->query($sql) === TRUE) {
            echo "Record inserted successfully for Recruiter";
            $recruiterID = $conn->insert_id; // Get the auto-incremented ID of the new recruiter.

            // Get info from contact fields.
            $contactFirstName = $_POST["contactFirstName"] ?? '';
            $contactLastName = $_POST["contactLastName"] ?? '';
            $contactPhoneNumber = $_POST["contactPhoneNumber"] ?? '';
            $contactEmail = $_POST["contactEmail"] ?? '';
            $contactPosition = $_POST["contactPosition"] ?? '';

            $contactSql = "INSERT INTO Contact (RecruiterID, FirstName, LastName, PhoneNumber, Email, Position)
                           VALUES ('$recruiterID', '$contactFirstName', '$contactLastName', '$contactPhoneNumber', '$contactEmail', '$contactPosition')";

            if ($conn->query($contactSql) === TRUE) {
                echo "Recruiter and Contact information inserted successfully.";
                $_SESSION['RecruiterID'] = $recruiterID;
                $_SESSION['username'] = $username;

                header("Location: add_position.php");

            } else {
                echo "Error inserting Contact information: " . $contactSql . "<br>" . $conn->error;
            }
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

    <title>Register as Recruiter</title>
</head>

<body>
<p id="login-link">Already have an account? <a href="login.php">Log in</a></p>

<h1>Register as Recruiter</h1>
<div class="form-container">


<form action="register_recruiter.php" method="post">
    <div class="single-line-input">
        <label for="username">Username:</label>
        <input type="text" name="username" pattern="^[a-zA-Z0-9_]{6,}$" title="Please use only alphanumeric characters and underscores." required><br>
    </div>

    <div class="single-line-input">
        <label for="password">Password:</label>
        <input type="password" name="password" pattern="^(?=.*\d)[a-zA-Z\d]{6,}$" title="Password must be at least 6 characters long and can contain letters and numbers" required><br>
    </div>

    <div class="single-line-input">
        <label for="recruiterEmail">Email:</label>
        <input type="email" name="recruiterEmail" required><br>
    </div>
    <h2>Recruiter info:</h2>

    <div class="two-column-input">
        <div class="column">
            <label for="recruiterName">Name:</label>
            <input type="text" name="recruiterName"><br>
        </div>
        <div class="column">
            <label for="recruiterCity">City:</label>
            <input type="text" name="recruiterCity"><br>
        </div>
    </div>

    <h2>Owner info:</h2>

    <div class="three-column-input">
        <div class="column">
            <label for="ownerFirstName">Owner First Name:</label>
            <input type="text" name="ownerFirstName"><br>
        </div>
        <div class="column">
            <label for="ownerLastName">Owner Last Name:</label>
            <input type="text" name="ownerLastName"><br>
        </div>
        <div class="column">
            <label for="ownerAddress">Owner Address:</label>
            <input type="text" name="ownerAddress"><br>
        </div>
    </div>

    <h2>Contact info:</h2>

    <div class="two-column-input">
        <div class="column">
            <label for="contactFirstName">Contact First Name:</label>
            <input type="text" name="contactFirstName" required><br>
        </div>
        <div class="column">
            <label for="contactLastName">Contact Last Name:</label>
            <input type="text" name="contactLastName" required><br>
        </div>
    </div>

    <div id="contact-number" class="single-line-input">
        <label for="contactPhoneNumber">Contact Phone Number:</label>
        <input type="text" name="contactPhoneNumber" pattern="^[0-9+]+$" title="Please use numbers and + only."><br>
    </div>

    <div class="single-line-input">
        <label for="contactEmail">Contact Email:</label>
        <input type="email" name="contactEmail"><br>
    </div>

    <div class="single-line-input">
        <label for="contactPosition">Position:</label>
        <input type="text" name="contactPosition"><br>
    </div>

    <input type="submit" value="Register">
</form>
    <p id="forward">Looking for a job? <a href="register.php">Register here to see the latest offers</a></p>
</div>
</body>
</html>
