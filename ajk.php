<?php
// Database connection
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "SAF"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $FULL_NAME = $_POST['name']; // Matches the name attribute in HTML
    $MATRIX_NUMBER = $_POST['matrixNo'];
    $IC_NUMBER = $_POST['icNo'];
    $GENDER = $_POST['gender'];
    $RELIGION = $_POST['religion'];
    $RACE = $_POST['race'];
    $EMAIL = $_POST['email'];
    $YEAR = $_POST['year'];
    $COURSE = $_POST['course'];
    $POSITION = $_POST['position'];
    $SEMESTER = $_POST['semester'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO AJK (FULL_NAME, MATRIX_NUMBER, IC_NUMBER, GENDER, RELIGION, RACE, EMAIL, YEAR, COURSE, POSITION, SEMESTER) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $FULL_NAME, $MATRIX_NUMBER, $IC_NUMBER, $GENDER, $RELIGION, $RACE, $EMAIL, $YEAR, $COURSE, $POSITION, $SEMESTER);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful'); window.location.href = 'display.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>