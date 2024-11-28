<?php 
session_start();
header('Content-Type: application/json');

include("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from POST request
    if (isset($_POST['id'], $_POST['name'], $_POST['reason'], $_POST['explaination'], $_POST['status'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $reason = $_POST['reason'];
        $explaination = $_POST['explaination']; 
        $status = $_POST['status'];
    
        // Prepare the SQL statement to insert the request
        $query = "INSERT INTO request (id, name, reason, explaination, status) 
                  VALUES ('$id', '$name', '$reason', '$explaination', '$status')";
    

        if (mysqli_query($con, $query)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
