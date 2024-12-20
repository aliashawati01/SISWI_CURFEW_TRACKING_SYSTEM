
<?php  
session_start();
include("database.php");

// Function to insert student data with duplicate check
function insert_student($con, $stud_id, $stud_email, $stud_name, $stud_password, $stud_sem, $stud_ic, $stud_NoTel, $stud_NoRum) {
    // Check for duplicates
    $check_sql = $con->prepare("SELECT id FROM student WHERE id = ? OR email = ?");
    $check_sql->bind_param('ss', $stud_id, $stud_email);
    $check_sql->execute();
    $check_sql->store_result();
    
    if ($check_sql->num_rows > 0) {
        // Duplicate found
        echo "<script>alert('Duplicate entry found for Student ID or Email. Please check and try again.');</script>";
        return false;
    }
    
    // No duplicate, proceed to insert
    $sql = $con->prepare("INSERT INTO student (id, email, name, password, sem, ic, notel, rumah) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param('ssssssss', $stud_id, $stud_email, $stud_name, $stud_password, $stud_sem, $stud_ic, $stud_NoTel, $stud_NoRum);
    return $sql->execute();
}

// Function to insert parent data
function insert_parent($con, $student_id, $parent_name, $parent_phone, $parent_email, $relation) {
    $sql = $con->prepare("INSERT INTO parent (student_id, name, phone, email, relation) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param('sssss', $student_id, $parent_name, $parent_phone, $parent_email, $relation);
    return $sql->execute();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['email'])) {
        $stud_id = $_POST['id'];
        $stud_email = $_POST['email'];
        $stud_name = strtoupper($_POST['name']);
        $stud_password = $_POST['password'];
        $stud_sem = $_POST['sem'];
        $stud_ic = $_POST['ic'];
        $stud_NoTel = $_POST['notel'];
        $stud_NoRum = $_POST['rumah'];

        // Insert student data
        if (insert_student($con, $stud_id, $stud_email, $stud_name, $stud_password, $stud_sem, $stud_ic, $stud_NoTel, $stud_NoRum)) {
            // Get the last inserted student ID
            $student_id = $stud_id;

            // Parent data
            $parent_name = $_POST['parent_name'];
            $parent_phone = $_POST['parent_phone'];
            $parent_email = $_POST['parent_email'];
            $relation = $_POST['relation'];

            // Insert parent data
            if (insert_parent($con, $student_id, $parent_name, $parent_phone, $parent_email, $relation)) {
                echo "<script>alert('New Student and Parent Data Successfully Added');</script>";
            } else {
                echo "<script>alert('Failed to add parent data.');</script>";
            }
        } else {
            echo "<script>alert('Failed to add new student data due to duplicate entry.');</script>";
        }
    }
}

// Check if the CSV file is uploaded
if (isset($_POST['import_csv'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $import_success = true; // Variable to track overall success
        
        // Open the file for reading
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // CSV columns as expected
                $stud_name = strtoupper($data[0]);
                $stud_id = $data[1];
                $stud_ic = $data[2];
                $stud_email = $data[3];
                $stud_NoTel = $data[4];
                $stud_NoRum = $data[5];
                $stud_password = $data[6];
                $stud_sem = $data[7];
                $parent_name = $data[8];
                $parent_phone = $data[9];
                $parent_email = $data[10];
                $relation = $data[11];

                // Insert student data
                if (insert_student($con, $stud_id, $stud_email, $stud_name, $stud_password, $stud_sem, $stud_ic, $stud_NoTel, $stud_NoRum)) {
                    // Insert parent data
                    if (!insert_parent($con, $stud_id, $parent_name, $parent_phone, $parent_email, $relation)) {
                        echo "<script>alert('Failed to add parent data for student ID: $stud_id');</script>";
                        $import_success = false; // Parent data insertion failed
                    }
                } else {
                    echo "<script>alert('Failed to add student data for student ID: $stud_id');</script>";
                    $import_success = false; // Student data insertion failed
                }
                $row++;
            }
            fclose($handle);
            
            // Show the final success message if all rows were imported successfully
            if ($import_success) {
                echo "<script>alert('CSV data has been successfully imported!');</script>";
            } else {
                echo "<script>alert('Some errors occurred while importing the CSV. Please check the logs.');</script>";
            }
        } else {
            echo "<script>alert('Error opening the file.');</script>";
        }
    } else {
        echo "<script>alert('No CSV file uploaded or an error occurred.');</script>";
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <title>Student Add</title>
    <link rel="icon" type="image/png" href="icons/sictrackslogo.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sidebar.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,body {
            background: #f5f5ff;
            margin-top: 24px;
            margin-bottom: 0px;
        }
        .container {
            margin: 1% 0 5% 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 100%;
        }
        .container1 {
            margin: 2% 0 1% 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 100%;
        }
        .index {
            margin: 5% 0 1% 16%;
            max-width: 82%;
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
        .title {
      font-family: "Times New Roman", Times, serif;
      font-size: 45px;
      color: black;
      text-align: center;
      font-weight: bold;
    }

    </style>
</head>
<body>

<aside>
  <img src="icons/UptmLogo.png" alt="Logo UPTM" style="margin-left:17px;margin-bottom:25px;margin-top:4px;width:160px;height:75px;">
  <a href="dashboard.php" >DASHBOARD</a>
  <a href="AddStudent.php" class='active'>ADD STUDENT DATA</a>
  <a>STUDENT LIST</a>
  <a href="admindata.php" style="margin-left:40px;">ADMIN</a>
  <a href="StudentData.php" style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php">STUDENT REQUEST</a>
  <a href="TrackStud.php" >TRACKING STUDENT</a>
  <a href="faq.php" ></i>FAQ</a>
</aside>

<div class="index">
<h2 class="title">NEW STUDENT</h2>
<div class="container1">
<h4 class="text-center mt-4">Import CSV Data</h4>
<form method="POST" enctype="multipart/form-data" class="text-center">
    <input type="file" name="csv_file" accept=".csv" class="form-control" required>
    <input type="submit" name="import_csv" value="Import CSV" class="btn btn-primary mt-3">
</form>
    </div>
<div class="container">
    <h4>Student Information Form</h4>
    <form method="POST" style="width:100%;">

        <table class="details-table">
            <!-- Student Information -->
            <tr>
                <th>Full Name</th>
                <td><input type="text" class="form-control" name="name" required oninput="this.value = this.value.toUpperCase()"></td>
            </tr>
            <tr>
                <th>Student ID</th>
                <td><input type="text" class="form-control" name="id" required></td>
            </tr>
            <tr>
                <th>IC Number</th>
                <td><input type="text" class="form-control" name="ic" required inputmode="numeric" pattern="[0-9]*" maxlength="12"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" class="form-control" name="email" required></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><input type="tel" class="form-control" name="notel" required inputmode="numeric" pattern="[0-9]*" maxlength="15"></td>
            </tr>
            <tr>
                <th>Room Number</th>
                <td>
                    <select class="form-control" name="rumah" required>
                        <option value="">Select Room</option>
                        <option value="A-1-1">A-1-1</option>
                        <option value="A-1-2">A-1-2</option>
                        <option value="A-1-3">A-1-3</option>
                        <option value="A-1-4">A-1-4</option>
                        <option value="A-1-5">A-1-5</option>
                        <option value="A-1-6">A-1-6</option>
                        <option value="A-1-7">A-1-7</option>
                        <option value="A-1-8">A-1-8</option>
                        <option value="A-1-9">A-1-9</option>
                        <option value="A-1-10">A-1-10</option>
                        <option value="A-1-11">A-1-11</option>
                        <option value="A-1-12">A-1-12</option>
                        <option value="A-1-13">A-1-13</option>
                        <option value="A-1-14">A-1-14</option>
                        <option value="A-2-1">A-2-1</option>
                        <option value="A-2-2">A-2-2</option>
                        <option value="A-2-3">A-2-3</option>
                        <option value="A-2-4">A-2-4</option>
                        <option value="A-2-5">A-2-5</option>
                        <option value="A-2-6">A-2-6</option>
                        <option value="A-2-7">A-2-7</option>
                        <option value="A-2-8">A-2-8</option>
                        <option value="A-2-9">A-2-9</option>
                        <option value="A-2-10">A-2-10</option>
                        <option value="A-2-11">A-2-11</option>
                        <option value="A-2-12">A-2-12</option>
                        <option value="A-2-13">A-2-13</option>
                        <option value="A-2-14">A-2-14</option>
                        <option value="A-3-1">A-3-1</option>
                        <option value="A-3-2">A-3-2</option>
                        <option value="A-3-3">A-3-3</option>
                        <option value="A-3-4">A-3-4</option>
                        <option value="A-3-5">A-3-5</option>
                        <option value="A-3-6">A-3-6</option>
                        <option value="A-3-7">A-3-7</option>
                        <option value="A-3-8">A-3-8</option>
                        <option value="A-3-9">A-3-9</option>
                        <option value="A-3-10">A-3-10</option>
                        <option value="A-3-11">A-3-11</option>
                        <option value="A-3-12">A-3-12</option>
                        <option value="A-3-13">A-3-13</option>
                        <option value="A-3-14">A-3-14</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" class="form-control" name="password" required></td>
            </tr>
            <tr>
                <th>Semester</th>
                <td>
                    <select class="form-control" name="sem" required>
                        <option value="">Select Semester</option>
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
        <h4 class="text-center mt-4">Parent Information Form</h4>
        <table class="details-table">
            <tr>
                <th>Parent Full Name</th>
                <td><input type="text" class="form-control" name="parent_name" required oninput="this.value = this.value.toUpperCase()"></td>
            </tr>
            <tr>
                <th>Parent Phone Number</th>
                <td><input type="tel" class="form-control" name="parent_phone" required inputmode="numeric" pattern="[0-9]*" maxlength="15"></td>
            </tr>
            <tr>
                <th>Parent Email</th>
                <td><input type="email" class="form-control" name="parent_email" required></td>
            </tr>
            <tr>
                <th>Relation</th>
                <td>
                    <label><input type="radio" name="relation" value="Father" required> Father</label>
                    <label><input type="radio" name="relation" value="Mother"> Mother</label>
                    <label><input type="radio" name="relation" value="Guardian"> Guardian</label>
                </td>
            </tr>
        </table>

        <div class="text-center mt-4">
            <input type="submit" name="update_student" value="Add Student" class="btn btn-primary">
        </div>
    </form>
</div>
</div>  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<footer>
    <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
</footer>

</body>
</html>