<?php
// Connect to the database
$host = "localhost";
$db_user = "root";
$db_password = ""; // Make sure to set your actual password if applicable
$db_name = "siswi"; // Use your actual database name

// Create a connection
$con = new mysqli($host, $db_user, $db_password, $db_name);

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// If you are filtering by username (or any other parameter), get it from the request
// You can get this from a POST or GET request
// Assuming you send the student username as a GET parameter 'username'
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Create the query to get the student data (assuming the table is called 'student')
$sql = "SELECT id FROM student WHERE username = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

// Check if any rows are returned
if ($result->num_rows > 0) {
    // Fetch the data as an associative array
    $student_data = $result->fetch_assoc();
    
    // Return the data as JSON
    echo json_encode([$student_data]);
} else {
    // Return an error message if no student found
    echo json_encode(["error" => "No student found with the given username"]);
}

// Close the connection
$con->close();
?>
