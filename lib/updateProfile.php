<?php
header('Content-Type: application/json');
include("database.php"); // Include your database connection

// Check if all required data is provided
if (isset($_POST['id']) && isset($_POST['email']) && isset($_POST['name']) && isset($_POST['password']) && isset($_POST['ic']) && isset($_POST['sem']) && isset($_POST['notel']) && isset($_POST['rumah'])) {
    
    // Capture data from POST request
    $id = $_POST['id'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $ic = $_POST['ic'];
    $sem = $_POST['sem'];
    $notel = $_POST['notel'];
    $rumah = $_POST['rumah'];

    // Write SQL to update student data based on the ID
    $sql = "UPDATE student 
            SET email = '$email', 
                name = '$name', 
                password = '$password', 
                ic = '$ic', 
                sem = '$sem', 
                notel = '$notel', 
                rumah = '$rumah'
            WHERE id = '$id'";

    // Execute the SQL query
    if (mysqli_query($con, $sql)) {
        // If the update was successful, send a success response
        echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
    } else {
        // If there was an error with the query, send an error response
        echo json_encode(["status" => "error", "message" => "Failed to update profile: " . mysqli_error($con)]);
    }

} else {
    // If any required fields are missing, return an error
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
