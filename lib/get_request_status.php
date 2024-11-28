<?php
header('Content-Type: application/json');
require 'database.php'; // Update with your actual database connection file

// Get the user ID from the POST request
$user_id = $_POST['id'] ?? '';

if (empty($user_id)) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit();
}

// Fetch the latest request status for the user
$query = "SELECT status 
          FROM request 
          WHERE id = ? 
          ORDER BY idreq DESC 
          LIMIT 1";

$stmt = $con->prepare($query);
$stmt->bind_param('s', $user_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'request_status' => $row['status']]);
    } else {
        // Return a success status with no request status when no records are found
        echo json_encode(['status' => 'success', 'request_status' => '']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
}


$stmt->close();
$con->close();
?>
