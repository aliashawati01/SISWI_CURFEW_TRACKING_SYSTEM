<?php
include("database.php");

if (isset($_POST['idreq']) && isset($_POST['status'])) {
    $idreq = $_POST['idreq'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE request SET status = '$status' WHERE idreq = '$idreq'";
    if (mysqli_query($con, $updateQuery)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
