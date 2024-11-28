<?php
header('Content-Type: application/json');
include_once("database.php");

$id = $_POST['id'];

if (!$con) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$sql = "SELECT id, date, checkin, checkout FROM tracking WHERE id = ? ORDER BY date DESC";
$stmt = $con->prepare($sql);

// Check if the statement prepared successfully
if ($stmt === false) {
    echo json_encode(["error" => "Failed to prepare the statement: " . $con->error]);
    exit;
}

$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'date' => $row['date'],
        'checkin' => $row['checkin'] ?? '--/--',
        'checkout' => $row['checkout'] ?? '--/--',
    ];
}

$stmt->close();
$con->close();

echo json_encode($data);
?>