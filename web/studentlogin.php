<?php
header('Content-Type: application/json');
include("database.php");

$response = array();

// Check if 'id' and 'password' are set in the request
if (isset($_POST['id']) && isset($_POST['password'])) {
    $stud_id = (int) $_POST['id']; // Cast id to an integer
    $stud_password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM student WHERE id = ? AND password = ?");
    
    // Bind parameters: 'i' for integer, 's' for string
    $stmt->bind_param("is", $stud_id, $stud_password);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch all rows and return them as an array
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        } else {
            // No user found
            $response['error'] = "Invalid id or password";
        }
    } else {
        // Query execution error
        $response['error'] = "Database query failed";
    }
    
    $stmt->close();
} else {
    // Missing id or password
    $response['error'] = "Missing id or password";
}

// Return the response as JSON
echo json_encode($response);

?>
