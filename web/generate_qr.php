<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_tracking";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $date_time = $_POST['date_time'];

    $sql = "INSERT INTO tracking (student_id, date_time) VALUES ('$student_id', '$date_time')";

    if ($con->query($sql) === TRUE) {
        echo "Record saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>
