<?php
include("database.php");

$sortName = $_GET['name'] ?? 'asc';
$sortDate = $_GET['date'] ?? 'desc';

$query = "SELECT * FROM tracking ORDER BY ";
$query .= $sortName === 'asc' ? "name ASC" : "name DESC";
$query .= ", ";
$query .= $sortDate === 'asc' ? "date ASC" : "date DESC";

$result = mysqli_query($con, $query);

$output = "";
while ($row = mysqli_fetch_assoc($result)) {
    $output .= "<tr>";
    $output .= "<td>{$row['id']}</td>";
    $output .= "<td>{$row['name']}</td>";
    $output .= "<td>{$row['checkout']}</td>";
    $output .= "<td>{$row['checkin']}</td>";
    $output .= "<td>{$row['date']}</td>";
    $output .= "<td>{$row['notel']}</td>";
    $output .= "</tr>";
}

echo $output;
?>
