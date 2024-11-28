<?php 
session_start();
include("database.php"); // Ensure this file connects to the database

if (isset($_GET['id'])) {
  $admin_id = mysqli_real_escape_string($con, $_GET['id']);
  $query = "SELECT * FROM admin WHERE id='$admin_id'";
  $query_run = mysqli_query($con, $query);
  if (mysqli_num_rows($query_run) > 0) {
    $admin = mysqli_fetch_array($query_run);
    $admin_name = $admin['name'];
    $admin_email = $admin['email'];
    $admin_password = $admin['password'];
  } else {
    echo "<div class='alert alert-danger'>No admin found with the given ID.</div>";
  }
}
  
if (isset($_POST['update_admin']) && isset($_GET['id'])) {
  // Update admin data
  $admin_id = mysqli_real_escape_string($con, $_GET['id']);
  $admin_name = mysqli_real_escape_string($con, $_POST['name']);
  $admin_email = mysqli_real_escape_string($con, $_POST['email']);
  $admin_password = mysqli_real_escape_string($con, $_POST['password']);

  // Update query for admin
  $query = "UPDATE admin SET name='$admin_name', email='$admin_email', password='$admin_password' WHERE id='$admin_id'";


  // Execute the queries
  $admin_update_run = mysqli_query($con, $query);

  if ($admin_update_run && $parent_update_run) {
      // Set success message in session
      $_SESSION['status'] = "admin and Parent information updated successfully";
      header("Location: editadmin.php?id=$admin_id");
      exit(0);
  } else {
      // Set error message in session
      $_SESSION['status'] = "Failed to update information";
      header("Location: editadmin.php?id=$admin_id");
      exit(0);
  }
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>admin Edit</title>
    <link rel="icon" type="image/png" href="icons/sictrackslogo.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sidebar.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5ff;
            color: #333;
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 5% 5% 5% 8%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 86%;
        }
        .card-header {
            background-color: #394867;
            color: #fff;
        }
        .card-body {
            padding: 30px;
        }
        h4 {
            text-align: center;
            color: #394867;
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
        .btn-danger {
            background-color: #e74c3c;
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .btn-primary {
            background-color: #1763af;
            border: none;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #0e4977;
        }
    </style>
</head>

<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    var showPassword = document.getElementById("showPassword");
    if (showPassword.checked) {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}
</script>


<body>

<div class="container">
    <h4>admin Edit</h4>
    <form action="editadmin.php?id=<?php echo $admin_id; ?>" method="POST">

        <table class="details-table">
            <!-- admin Information -->
            <tr>
                <th>Full Name</th>
                <td><input type="text" class="form-control" name="name" value="<?php echo $admin_name; ?>" required oninput="this.value = this.value.toUpperCase()"></td>
            </tr>
            <tr>
                <th>Admin ID</th>
                <td><input type="text" class="form-control" name="id" value="<?php echo $admin_id; ?>" readonly></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" class="form-control" name="email" value="<?php echo $admin_email; ?>" required></td>
            </tr>
            <tr>
            <th>Password</th>
             <td>
                <input type="password" class="form-control" name="password" id="password" value="<?php echo $admin_password; ?>" required>
                <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
             </td>
        </table>

        <div class="text-center mt-4">
            <input type="submit" name="update_admin" value="Update admin" class="btn btn-primary">
            <a href="adminData.php" class="btn btn-danger float-end">BACK</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
</footer>

</body>
</html>
