<?php
include("database.php");

$query = "SELECT * FROM tracking ORDER BY trackid DESC";
$result = mysqli_query($con, $query);

while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['checkout']}</td>
            <td>{$row['checkin']}</td>
            <td>{$row['date']}</td>
            <td class='" . ($row['status'] == 'Late' ? 'status-late' : '') . "'>{$row['status']}</td>
        </tr>";
}
?>
