<?php
// Set the timezone to Kuala Lumpur
date_default_timezone_set('Asia/Kuala_Lumpur');

// Connection to database
include_once("database.php");

$id = $_POST['id']; // Student ID passed from the app
$current_date = date('Y-m-d'); // Current date in 'YYYY-MM-DD' format

if (!$con) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Query to fetch the latest record for the current day, ensuring same trackid
$sql = "SELECT 
            trackid,
            checkin,
            checkout
        FROM tracking
        WHERE id = ? AND DATE(date) = ?
        ORDER BY trackid DESC 
        LIMIT 1";

$stmt = $con->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $con->error]);
    exit;
}

// Bind the parameters
$stmt->bind_param('ss', $id, $current_date);
$stmt->execute();
$result = $stmt->get_result();

// Prepare output
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Format the times or use default placeholders
    $checkin_time = empty($row['checkin']) ? '--:--:--' : date('H:i:s', strtotime($row['checkin']));
    $checkout_time = empty($row['checkout']) ? '--:--:--' : date('H:i:s', strtotime($row['checkout']));

    echo json_encode([
        'checkin' => $checkin_time,
        'checkout' => $checkout_time,
        'trackid' => $row['trackid'] // Optional: Include trackid for debugging
    ]);
} else {
    // Return default values if no records found
    echo json_encode([
        'checkin' => '--:--:--',
        'checkout' => '--:--:--',
    ]);
}

// Debugging output
error_log("Query result: " . json_encode([
    'checkin' => $checkin_time,
    'checkout' => $checkout_time
]));
?>
