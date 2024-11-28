<?php 
session_start();
include("database.php"); // Ensure this file connects to the database

if (isset($_GET['id'])) {
  $stud_id = mysqli_real_escape_string($con, $_GET['id']);
  $query = "SELECT * FROM student WHERE id='$stud_id'";
  $query_run = mysqli_query($con, $query);
  if (mysqli_num_rows($query_run) > 0) {
    $student = mysqli_fetch_array($query_run);
    $stud_name = $student['name'];
    $stud_email = $student['email'];
    $stud_ic = $student['ic'];
    $stud_notel = $student['notel'];
    $stud_rumah = $student['rumah'];
    $stud_password = $student['password'];
    $stud_sem = $student['sem'];
  } else {
    echo "<div class='alert alert-danger'>No student found with the given ID.</div>";
  }

  // Fetch parent data
  $parent_query = "SELECT * FROM parent WHERE student_id='$stud_id'";
  $parent_query_run = mysqli_query($con, $parent_query);
  $parent = mysqli_fetch_array($parent_query_run);
  $parent_name = $parent['name'] ?? '';
  $parent_phone = $parent['phone'] ?? '';
  $parent_email = $parent['email'] ?? '';
  $parent_relation = $parent['relation'] ?? '';

} else {
  echo "<div class='alert alert-danger'>No student ID provided.</div>";
}

if (isset($_POST['update_student']) && isset($_GET['id'])) {
  // Update student data
  $stud_id = mysqli_real_escape_string($con, $_GET['id']);
  $stud_name = mysqli_real_escape_string($con, $_POST['name']);
  $stud_ic = mysqli_real_escape_string($con, $_POST['ic']);
  $stud_email = mysqli_real_escape_string($con, $_POST['email']);
  $stud_notel = mysqli_real_escape_string($con, $_POST['notel']);
  $stud_rumah = mysqli_real_escape_string($con, $_POST['rumah']);
  $stud_password = mysqli_real_escape_string($con, $_POST['password']);
  $stud_sem = mysqli_real_escape_string($con, $_POST['sem']);

  // Update query for student
  $query = "UPDATE student SET name='$stud_name', ic='$stud_ic', email='$stud_email', notel='$stud_notel', rumah='$stud_rumah', password='$stud_password', sem='$stud_sem' WHERE id='$stud_id'";

  // Update parent query
  $parent_name = mysqli_real_escape_string($con, $_POST['parent_name']);
  $parent_phone = mysqli_real_escape_string($con, $_POST['parent_phone']);
  $parent_email = mysqli_real_escape_string($con, $_POST['parent_email']);
  
  // Get the relation from the radio buttons
  $parent_relation = isset($_POST['relation']) ? mysqli_real_escape_string($con, $_POST['relation']) : '';

  if ($parent) {
    // Update existing parent data
    $parent_query = "UPDATE parent SET name='$parent_name', phone='$parent_phone', email='$parent_email', relation='$parent_relation' WHERE student_id='$stud_id'";
  } else {
    // Insert new parent data if not present
    $parent_query = "INSERT INTO parent (student_id, name, phone, email, relation) VALUES ('$stud_id', '$parent_name', '$parent_phone', '$parent_email', '$parent_relation')";
  }

  // Execute the queries
  $student_update_run = mysqli_query($con, $query);
  $parent_update_run = mysqli_query($con, $parent_query);

  if ($student_update_run && $parent_update_run) {
      // Set success message in session
      $_SESSION['status'] = "Student and Parent information updated successfully";
      header("Location: editStudent.php?id=$stud_id");
      exit(0);
  } else {
      // Set error message in session
      $_SESSION['status'] = "Failed to update information";
      header("Location: editStudent.php?id=$stud_id");
      exit(0);
  }
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>Student Edit</title>
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
    <h4>Student Edit</h4>
    <form action="editStudent.php?id=<?php echo $stud_id; ?>" method="POST">

        <table class="details-table">
            <!-- Student Information -->
            <tr>
                <th>Full Name</th>
                <td><input type="text" class="form-control" name="name" value="<?php echo $stud_name; ?>" required oninput="this.value = this.value.toUpperCase()"></td>
            </tr>
            <tr>
                <th>Student ID</th>
                <td><input type="text" class="form-control" name="id" value="<?php echo $stud_id; ?>" readonly></td>
            </tr>
            <tr>
                <th>IC Number</th>
                <td><input type="text" class="form-control" name="ic" value="<?php echo $stud_ic; ?>" required></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" class="form-control" name="email" value="<?php echo $stud_email; ?>" required></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><input type="tel" class="form-control" name="notel" value="<?php echo $stud_notel; ?>" required></td>
            </tr>
            <tr>
                <th>House Number</th>
                <td>
                    <select class="form-control" name="rumah" required>
                        <option value="">Select House</option>
                        <option value="A-1-1" <?php if ($stud_rumah == 'A-1-1') echo 'selected'; ?>>A-1-1</option>
                        <option value="A-1-2" <?php if ($stud_rumah == 'A-1-2') echo 'selected'; ?>>A-1-2</option>
                        <option value="A-1-3" <?php if ($stud_rumah == 'A-1-3') echo 'selected'; ?>>A-1-3</option>
                        <option value="A-1-4" <?php if ($stud_rumah == 'A-1-4') echo 'selected'; ?>>A-1-4</option>
                        <option value="A-1-5" <?php if ($stud_rumah == 'A-1-5') echo 'selected'; ?>>A-1-5</option>
                        <option value="A-1-6" <?php if ($stud_rumah == 'A-1-6') echo 'selected'; ?>>A-1-6</option>
                        <option value="A-1-7" <?php if ($stud_rumah == 'A-1-7') echo 'selected'; ?>>A-1-7</option>
                        <option value="A-1-8" <?php if ($stud_rumah == 'A-1-8') echo 'selected'; ?>>A-1-8</option>
                        <option value="A-1-9" <?php if ($stud_rumah == 'A-1-9') echo 'selected'; ?>>A-1-9</option>
                        <option value="A-1-10" <?php if ($stud_rumah == 'A-1-10') echo 'selected'; ?>>A-1-10</option>
                        <option value="A-1-11" <?php if ($stud_rumah == 'A-1-11') echo 'selected'; ?>>A-1-11</option>
                        <option value="A-1-12" <?php if ($stud_rumah == 'A-1-12') echo 'selected'; ?>>A-1-12</option>
                        <option value="A-1-13" <?php if ($stud_rumah == 'A-1-13') echo 'selected'; ?>>A-1-13</option>
                        <option value="A-1-14" <?php if ($stud_rumah == 'A-1-14') echo 'selected'; ?>>A-1-14</option>
                        <option value="A-2-1" <?php if ($stud_rumah == 'A-1-1') echo 'selected'; ?>>A-1-1</option>
                        <option value="A-2-2" <?php if ($stud_rumah == 'A-1-2') echo 'selected'; ?>>A-1-2</option>
                        <option value="A-2-3" <?php if ($stud_rumah == 'A-1-3') echo 'selected'; ?>>A-1-3</option>
                        <option value="A-2-4" <?php if ($stud_rumah == 'A-1-4') echo 'selected'; ?>>A-1-4</option>
                        <option value="A-2-5" <?php if ($stud_rumah == 'A-1-5') echo 'selected'; ?>>A-1-5</option>
                        <option value="A-2-6" <?php if ($stud_rumah == 'A-1-6') echo 'selected'; ?>>A-1-6</option>
                        <option value="A-2-7" <?php if ($stud_rumah == 'A-1-7') echo 'selected'; ?>>A-1-7</option>
                        <option value="A-2-8" <?php if ($stud_rumah == 'A-1-8') echo 'selected'; ?>>A-1-8</option>
                        <option value="A-2-9" <?php if ($stud_rumah == 'A-1-9') echo 'selected'; ?>>A-1-9</option>
                        <option value="A-2-10" <?php if ($stud_rumah == 'A-1-10') echo 'selected'; ?>>A-1-10</option>
                        <option value="A-2-11" <?php if ($stud_rumah == 'A-1-11') echo 'selected'; ?>>A-1-11</option>
                        <option value="A-2-12" <?php if ($stud_rumah == 'A-1-12') echo 'selected'; ?>>A-1-12</option>
                        <option value="A-2-13" <?php if ($stud_rumah == 'A-1-13') echo 'selected'; ?>>A-1-13</option>
                        <option value="A-2-14" <?php if ($stud_rumah == 'A-1-14') echo 'selected'; ?>>A-1-14</option>
                        <option value="A-3-1" <?php if ($stud_rumah == 'A-1-1') echo 'selected'; ?>>A-1-1</option>
                        <option value="A-3-2" <?php if ($stud_rumah == 'A-1-2') echo 'selected'; ?>>A-1-2</option>
                        <option value="A-3-3" <?php if ($stud_rumah == 'A-1-3') echo 'selected'; ?>>A-1-3</option>
                        <option value="A-3-4" <?php if ($stud_rumah == 'A-1-4') echo 'selected'; ?>>A-1-4</option>
                        <option value="A-3-5" <?php if ($stud_rumah == 'A-1-5') echo 'selected'; ?>>A-1-5</option>
                        <option value="A-3-6" <?php if ($stud_rumah == 'A-1-6') echo 'selected'; ?>>A-1-6</option>
                        <option value="A-3-7" <?php if ($stud_rumah == 'A-1-7') echo 'selected'; ?>>A-1-7</option>
                        <option value="A-3-8" <?php if ($stud_rumah == 'A-1-8') echo 'selected'; ?>>A-1-8</option>
                        <option value="A-3-9" <?php if ($stud_rumah == 'A-1-9') echo 'selected'; ?>>A-1-9</option>
                        <option value="A-3-10" <?php if ($stud_rumah == 'A-1-10') echo 'selected'; ?>>A-1-10</option>
                        <option value="A-3-11" <?php if ($stud_rumah == 'A-1-11') echo 'selected'; ?>>A-1-11</option>
                        <option value="A-3-12" <?php if ($stud_rumah == 'A-1-12') echo 'selected'; ?>>A-1-12</option>
                        <option value="A-3-13" <?php if ($stud_rumah == 'A-1-13') echo 'selected'; ?>>A-1-13</option>
                        <option value="A-3-14" <?php if ($stud_rumah == 'A-1-14') echo 'selected'; ?>>A-1-14</option>
        </select>
    </td>



            </tr>
            <tr>
            <th>Password</th>
             <td>
                <input type="password" class="form-control" name="password" id="password" value="<?php echo $stud_password; ?>" required>
                <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
             </td>
            <tr>
                <th>Semester</th>
                <td>
                    <select id="sem" class="form-control" name="sem" required>
                        <option value=""><?php echo $stud_sem; ?></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>
                </td>
            </tr>
        </table>

        <!-- Parent Information -->
        <h4 class="text-center mt-4">Parent Information</h4>
        <table class="details-table">
            <tr>
                <th>Parent Full Name</th>
                <td><input type="text" class="form-control" name="parent_name" value="<?php echo $parent_name; ?>" required oninput="this.value = this.value.toUpperCase()"></td>
            </tr>
            <tr>
                <th>Parent Phone Number</th>
                <td><input type="tel" class="form-control" name="parent_phone" value="<?php echo $parent_phone; ?>" required></td>
            </tr>
            <tr>
                <th>Parent Email</th>
                <td><input type="email" class="form-control" name="parent_email" value="<?php echo $parent_email; ?>" required></td>
            </tr>
            <tr>
                <th>Relation</th>
                <td><b>Relation</b><br>
    <label><input type="radio" name="relation" value="Father" <?php if($parent_relation == 'Father') echo 'checked'; ?> required> Father</label>
    <label><input type="radio" name="relation" value="Mother" <?php if($parent_relation == 'Mother') echo 'checked'; ?>> Mother</label>
    <label><input type="radio" name="relation" value="Guardian" <?php if($parent_relation == 'Guardian') echo 'checked'; ?>> Guardian</label></td>
            </tr>
        </table>

        <div class="text-center mt-4">
            <input type="submit" name="update_student" value="Update Student" class="btn btn-primary">
            <a href="StudentData.php" class="btn btn-danger float-end">BACK</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
</footer>

</body>
</html>
