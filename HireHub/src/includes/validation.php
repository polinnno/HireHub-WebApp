<?php
function isValidUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]+$/', $username);
}

function isUsernameUnique($username): bool {
    global $conn;
    $result = $conn->query("SELECT * FROM Candidate WHERE Username = '$username'");
    return $result->num_rows === 0;
}

function isValidPassword($password): bool {
    // Minimum length of 6 characters
    return strlen($password) >= 6;
}

function isEmailUnique($email): bool {
    global $conn;
    $result = $conn->query("SELECT * FROM Candidate WHERE Email = '$email'");
    return $result->num_rows === 0;
}
?>