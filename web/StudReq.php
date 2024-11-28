<?php
include("database.php");

if (isset($_POST['idreq']) && isset($_POST['status'])) {
    $idreq = mysqli_real_escape_string($con, $_POST['idreq']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $updateQuery = "UPDATE request SET status = '$status' WHERE idreq = '$idreq'";
    if (mysqli_query($con, $updateQuery)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
?>


<html lang="en">
<head>
  <title>SISWI CURFEW TRACKING SYSTEM</title>
  <link rel="icon" type="image/png" href="icons/sictrackslogo.png"/>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="sidebar.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */

    .container {
      margin-left: 220px;
      padding: 20px;
    }

    body {
      background: #f5f5ff;
      font-family: Arial, sans-serif;
    }

    .title {
      font-family: "Times New Roman", Times, serif;
      font-size: 45px;
      color: black;
      text-align: center;
      margin-top: 30px;
    }

    /* Table Styling */
    .tbl-header {
      background-color: rgba(255, 255, 255, 0.2);
      width: 100%;
      border-radius: 10px;
      border: 1px solid #ccc;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    th, td {
      padding: 12px;
      text-align: left;
      color: #333;
    }

    th {
      background-color: #394867;
      color: #fff;
    }

    td {
      background-color: rgba(185, 208, 235, 0.6);
    }

    tr:nth-child(even) td {
      background-color: rgba(185, 208, 235, 0.4);
    }

    tr:hover td {
      background-color: rgba(185, 208, 235, 1);
    }

    /* Button Group Styling */
    .btn-group {
      display: flex;
      gap: 5px;
    }


    /* On small screens, set height to 'auto' for the grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        width: 100%;
        height: auto;
      }

      .container {
        margin-left: 0;
      }
    }
  </style>
</head>

<script>
$(document).ready(function () {
    $('.action-btn').click(function () {
        var idreq = $(this).data('id');
        var status = $(this).data('status');
        var row = $(this).closest('tr');

        $.ajax({
            url: 'updateRequest.php',
            method: 'POST',
            data: { idreq: idreq, status: status },
            success: function (response) {
                if (response.trim() === "success") {
                    // Remove the row from the current table
                    row.fadeOut(500, function () {
                        var newRow = row.clone(); // Clone the row
                        newRow.find('.btn-group').remove(); // Remove the action buttons
                        newRow.find('td:last-child').text(status); // Update the status cell with the new status
                        
                        // Append to the appropriate table
                        var targetTable = status === 'approved'
                            ? $('h2:contains("STUDENT APPROVED")').next('.tbl-header').find('table')
                            : $('h2:contains("STUDENT DECLINED")').next('.tbl-header').find('table');
                        
                        newRow.hide(); // Hide the new row for a smooth effect
                        targetTable.append(newRow.fadeIn(500)); // Add the row to the target table with a fade-in
                        row.remove(); // Ensure the original row is fully removed
                    });
                } else {
                    alert('Error updating status. Please try again.');
                }
            },
            error: function () {
                alert('Failed to process the request. Please check your connection or try again.');
            }
        });
    });
});


</script>


<body>

<aside>
  <img src="icons/UptmLogo.png" alt="Logo UPTM" style="margin-left:17px;margin-bottom:25px;margin-top:4px;width:160px;height:75px;">
  <a href="dashboard.php" >DASHBOARD</a>
  <a href="AddStudent.php">ADD STUDENT DATA</a>
  <a>STUDENT LIST</a>
  <a href="admindata.php" style="margin-left:40px;">ADMIN</a>
  <a href="StudentData.php" style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php" class='active'>STUDENT REQUEST</a>
  <a href="TrackStud.php" >TRACKING STUDENT</a>
  <a href="faq.php" ></i>FAQ</a>
</aside>

<div class="container">
  <section>
    <h2 class="title">STUDENT REQUEST</h2>
    <div class="tbl-header">
      <table>
        <tr>
          <th>ID</th>
          <th>NAME</th>
          <th>REASON</th>
          <th>EXPLAINATION</th>
          <th>STATUS</th>
          <th>ACTION</th>
        </tr>
        <?php 
        $query = "SELECT * FROM request WHERE status = 'pending' ORDER BY idreq ASC";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($result)) { ?>
        <tr>
          <td><?php echo $row['idreq']; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['reason']; ?></td>
          <td><?php echo $row['explaination']; ?></td>
          <td><?php echo $row['status']; ?></td>
          <td>
  <div class="btn-group">
    <button class="action-btn" data-id="<?php echo $row['idreq']; ?>" data-status="approved">Approve</button>
    <button class="action-btn" data-id="<?php echo $row['idreq']; ?>" data-status="declined">Decline</button>
  </div>
</td>

        </tr>
        <?php } ?>
      </table>
    </div>
  </section>

  <section>
    <h2 class="title">STUDENT APPROVED</h2>
    <div class="tbl-header">
      <table>
        <tr>
          <th>ID</th>
          <th>NAME</th>
          <th>REASON</th>
          <th>EXPLAINATION</th>
          <th>STATUS</th>
        </tr>
        <?php 
        $query = "SELECT * FROM request WHERE status = 'approved' ORDER BY idreq ASC";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($result)) { ?>
        <tr>
          <td><?php echo $row['idreq']; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['reason']; ?></td>
          <td><?php echo $row['explaination']; ?></td>
          <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </section>

  <section>
    <h2 class="title">STUDENT DECLINED</h2>
    <div class="tbl-header">
      <table>
        <tr>
          <th>ID</th>
          <th>NAME</th>
          <th>REASON</th>
          <th>EXPLAINATION</th>
          <th>STATUS</th>
        </tr>
        <?php 
        $query = "SELECT * FROM request WHERE status = 'declined' ORDER BY idreq ASC";
        $result = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($result)) { ?>
        <tr>
          <td><?php echo $row['idreq']; ?></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['reason']; ?></td>
          <td><?php echo $row['explaination']; ?></td>
          <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </section>
</div>

<?php 
if (isset($_POST['approve'])) {
    $idreq = $_POST['idreq'];
    $updateQuery = "UPDATE request SET status = 'approved' WHERE idreq = '$idreq'";
    mysqli_query($con, $updateQuery);
    header("Location: StudReq.php");
}

if (isset($_POST['decline'])) {
    $idreq = $_POST['idreq'];
    $updateQuery = "UPDATE request SET status = 'declined' WHERE idreq = '$idreq'";
    mysqli_query($con, $updateQuery);
}
?>

<footer>
  <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
</footer>

</body>
</html>