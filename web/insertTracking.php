<?php 
include('database.php');
date_default_timezone_set('Asia/Kuala_Lumpur');

$id = $_POST['id'];
$current_time = date('H:i:s');
$current_date = date('Y-m-d');
$curfew_time = '23:59:59';  // Define curfew time

// Check if student exists
$check_student_query = "SELECT name, notel FROM student WHERE id = '$id'";
$check_student_result = mysqli_query($con, $check_student_query);

if (mysqli_num_rows($check_student_result) == 0) {
    echo "Error: Student ID does not exist.";
    exit;
}

$student_data = mysqli_fetch_assoc($check_student_result);
$name = $student_data['name'];
$notel = $student_data['notel'];

// Get the latest record for this student
$query = "SELECT * FROM tracking WHERE id = '$id' ORDER BY trackid DESC LIMIT 1";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

// Determine if the scan is late
$is_late = ($current_time > $curfew_time) ? 'Late' : 'On Time';

if ($row) {
    // If there is a record and checkin is NULL, update check-in
    if ($row['checkin'] == NULL && $row['date'] == $current_date) {
        $update_query = "UPDATE tracking SET checkin = '$current_time', status = '$is_late' WHERE trackid = " . $row['trackid'];
        if (mysqli_query($con, $update_query)) {
            echo "Student ID $id: Checked in successfully.";
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        // Insert a new record for check-out if a check-in already exists
        $insert_query = "INSERT INTO tracking (id, name, notel, date, checkout, checkin, status) 
        VALUES ('$id', '$name', '$notel', '$current_date', '$current_time', NULL, 'Pending ')";
        
        if (mysqli_query($con, $insert_query)) {
            echo "Student ID $id: Checked out successfully.";
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
} else {
    // First scan of the day, insert as "Pending Check-In" if no previous record exists
    $insert_query = "INSERT INTO tracking (id, name, notel, date, checkout, checkin, status) 
    VALUES ('$id', '$name', '$notel', '$current_date', '$current_time', NULL, 'Pending ')";
    
    if (mysqli_query($con, $insert_query)) {
        echo "Student ID $id: Checked out successfully. Status is Pending ";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
