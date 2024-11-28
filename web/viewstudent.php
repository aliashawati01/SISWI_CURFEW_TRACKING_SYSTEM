<?php
session_start();
include("database.php");

// Check if student ID is provided
if (!isset($_GET['id'])) {
    echo "<script>alert('Student ID is missing.'); window.location.href='StudentData.php';</script>";
    exit;
}

$student_id = $_GET['id'];

// Fetch student data from the database
$student_query = "SELECT * FROM student WHERE id = ?";
$student_stmt = $con->prepare($student_query);
$student_stmt->bind_param("s", $student_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student_data = $student_result->fetch_assoc();

if (!$student_data) {
    echo "<script>alert('Student not found.'); window.location.href='StudentData.php';</script>";
    exit;
}

// Fetch parent data associated with the student
$parent_query = "SELECT * FROM parent WHERE student_id = ?";
$parent_stmt = $con->prepare($parent_query);
$parent_stmt->bind_param("s", $student_id);
$parent_stmt->execute();
$parent_result = $parent_stmt->get_result();
?>

<html lang="en">
<head>
    <title>View Student Details</title>
    <link rel="icon" type="image/png" href="icons/sictrackslogo.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sidebar.css" />
    <style>
        body {
            background: #f5f5ff;
            color: #333;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-left: 14%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 100%;
        }
        h2 {
            text-align: center;
            color: #394867;
            font-size: 36px;
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            margin-top: 20px;
        }
        .details-table th, .details-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .details-table th {
            background-color: #394867;
            color: #fff;
            width: 30%;
        }
        .details-table td {
            background-color: #e9f1fa;
        }
    </style>
</head>
<body>

<aside>
  <img src="icons/UptmLogo.png" alt="Logo UPTM" style="margin-left:17px;margin-bottom:25px;margin-top:4px;width:160px;height:75px;">
  <a href="dashboard.php" >DASHBOARD</a>
  <a href="AddStudent.php">ADD STUDENT DATA</a>
  <a>STUDENT LIST</a>
  <a href="admindata.php" style="margin-left:40px;">ADMIN</a>
  <a href="StudentData.php" class='active' style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php">STUDENT REQUEST</a>
  <a href="TrackStud.php" >TRACKING STUDENT</a>
  <a href="faq.php" ></i>FAQ</a>
</aside>

<div class="container">
    <h2>Student Details</h2>
    <a href="javascript:history.back()" class="btn btn-danger float-end">BACK</a>

    <!-- Student Information -->
    <table class="details-table">
        <tr>
            <th>ID</th>
            <td><?php echo $student_data['id']; ?></td>
        </tr>
        <tr>
            <th>Full Name</th>
            <td><?php echo $student_data['name']; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $student_data['email']; ?></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><?php echo $student_data['password']; ?></td>
        </tr>
        <tr>
            <th>Semester</th>
            <td><?php echo $student_data['sem']; ?></td>
        </tr>
        <tr>
            <th>Identification Number</th>
            <td><?php echo $student_data['ic']; ?></td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td><?php echo $student_data['notel']; ?></td>
        </tr>
        <tr>
            <th>Room Number</th>
            <td><?php echo $student_data['rumah']; ?></td>
        </tr>
    </table>

    <h2 style="text-align:center; color:#394867; margin-top:40px;">Parent Information</h2>

    <!-- Parent Information -->
    <?php if ($parent_result->num_rows > 0): ?>
        <?php while ($parent_data = $parent_result->fetch_assoc()): ?>
            <table class="details-table">
                <tr>
                    <th>Full Name</th>
                    <td><?php echo $parent_data['name']; ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo $parent_data['phone']; ?></td>
                </tr>
                <tr>
    <th>Email</th>
    <td>
        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $parent_data['email']; ?>&su=Issue regarding <?php echo urlencode($student_data['name']); ?>&body=Dear <?php echo urlencode($parent_data['name']); ?>," 
           target="_blank" 
           style="color: #0066cc; text-decoration: underline;">
            <?php echo $parent_data['email']; ?>
        </a>
    </td>
</tr>
                <tr>
                    <th>Relation</th>
                    <td><?php echo $parent_data['relation']; ?></td>
                </tr>
            </table>
            <br>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color: #555;">No parent information available for this student.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Footer Section -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
</footer>

</body>
</html>

