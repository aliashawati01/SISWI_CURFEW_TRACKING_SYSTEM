<?php
session_start();
include("database.php");

if(isset($_POST['delete_student']))
{
    $stud_id = mysqli_real_escape_string($con, $_POST['delete_student']);

    $query = "DELETE FROM student WHERE id='$stud_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Student Deleted Successfully";
        header("Location: StudentData.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Student Not Deleted";
        header("Location: StudentData.php");
        exit(0);
    }
}



if(isset($_POST['save_student']))
{
    $stud_name = mysqli_real_escape_string($con, $_POST['name']);
    $stud_email = mysqli_real_escape_string($con, $_POST[' email']);
    $stud_password = mysqli_real_escape_string($con, $_POST['password']);
    $stud_sem = mysqli_real_escape_string($con, $_POST['sem']);
    $stud_ic = mysqli_real_escape_string($con, $_POST['ic']);
    $stud_NoTel = mysqli_real_escape_string($con, $_POST['notel']);
    $stud_NoRum = mysqli_real_escape_string($con, $_POST['rumah']);

    $query = "INSERT INTO student (email, name, password, sem, ic, notel, rumah) VALUES ('$stud_email','$stud_name','$stud_password','$stud_sem','$stud_ic','$stud_NoTel','$stud_NoRum')";


    $query_run = mysqli_query($con, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Student Created Successfully";
        header("Location: AddStudent.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Student Not Created";
        header("Location: AddStudent.php");
        exit(0);
    }
}

?>