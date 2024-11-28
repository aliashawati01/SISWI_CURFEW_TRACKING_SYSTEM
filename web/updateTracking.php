<?php
session_start();
include("database.php");

if(isset($_POST['id'])) {
    $id = $_POST['id'];

    // Check current status
    $query = "SELECT status FROM tracking WHERE id = '$id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    
    $newStatus = ($row['status'] === 'in') ? 'out' : 'in';
    $timeColumn = ($newStatus === 'in') ? 'checkin' : 'checkout';
    
    // Update with current time and new status
    $updateQuery = "UPDATE tracking SET $timeColumn = NOW(), status = '$newStatus' WHERE id = '$id'";
    mysqli_query($con, $updateQuery);
    
    echo "Student ID $id checked $newStatus successfully!";
}
?>
