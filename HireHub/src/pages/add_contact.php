<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

$recruiterID = $_SESSION['RecruiterID'] ?? 0;
include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['RecruiterID'])) {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $phoneNumber = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $position = $_POST["position"];

    // Insert data into Contact table
    $sql = "INSERT INTO Contact (RecruiterID, FirstName, LastName, PhoneNumber, Email, Position)
            VALUES ($recruiterID, '$firstName', '$lastName', '$phoneNumber', '$email', '$position')";

    if ($conn->query($sql) === TRUE) {
        echo "Contact added successfully.";
    } else {
        echo "Error adding contact: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/add_contact.css"> <!-- Adjust the path as needed -->
    <title>Add Contact</title>
</head>
<body>
<button id="back" onclick="goBack()">&#x2190; back</button>

<h1>Add Contact</h1>
<div class="form-container">
    <form action="add_contact.php" method="post">
        <div class="two-column-input">
            <div class="column">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" required><br>
            </div>
            <div class="column">
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" required><br>
            </div>
        </div>

        <div class="single-line-input">
            <label for="phoneNumber">Phone Number:</label>
            <input type="tel" name="phoneNumber" required><br>
        </div>
        <div class="single-line-input">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br>
        </div>
        <div class="single-line-input">
            <label for="position">Position:</label>
            <input type="text" name="position" required><br>
        </div>
        <input type="submit" value="Add Contact">
    </form>
</div>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>
