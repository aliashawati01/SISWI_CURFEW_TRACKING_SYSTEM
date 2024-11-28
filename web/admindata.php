<?php 
    session_start();
    include("database.php");

    // Initialize the search query based on user input
    $search_query = "";
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = mysqli_real_escape_string($con, $_GET['search']);
        $search_query = " WHERE id LIKE '%$search_term%' OR name LIKE '%$search_term%'";

    }

    // Fetch admin data from the database with the search filter
    $query = "SELECT * FROM admin" . $search_query;
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($con));
    }

    echo $query;
    if(isset($_POST['delete_admin']))
    {
        $admin_id = mysqli_real_escape_string($con, $_POST['delete_admin']);
    
        $query = "DELETE FROM admin WHERE id='$admin_id' ";
        $query_run = mysqli_query($con, $query);
    
        if($query_run)
        {
            $_SESSION['message'] = "Admin Deleted Successfully";
            header("Location: admindata.php");
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Admin Not Deleted";
            header("Location: admindata.php");
            exit(0);
        }
    }
?>


<html lang="en">
<head>
    <title>SISWI CURFEW TRACKING SYSTEM</title>
    <link rel="icon" type="image/png" href="icons/sictrackslogo.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sidebar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        /* Main container for the content */
        .container {
            margin-left: 14%;
            padding: 20px;
        }

        body {
            background:#f5f5ff;
        }

        html, body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

       /* Table Styling */
    .tbl-header {
      background-color: rgba(255, 255, 255, 0.2);
      margin-left: 30px;
      width: 98%;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      font-size: 14px;
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
      background-color: rgba(185, 208, 235, 0.7);
    }   
        /* Button group adjustments */
        .btn-group {
            display: flex;
            gap: 5px;
        }

        .title {
            font-family: "Times New Roman", Times, serif;
            font-size: 45px;
            margin-left: 30px;
            color: black;
            text-align: center;
        }

        .download-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

      /* Search container */
    .search-container {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px; /* Space between elements */
        margin-bottom: 20px;
        margin-left: 3%;
    }

    /* Search input box */
    .search-container input {
    padding: 10px;
    font-size: 16px;
    width: 300px;
    border: 2px solid #394867;
    border-radius: 30px;
    outline: none;
    transition: all 0.3s ease;
    }

    /* Search input box focus effect */
    .search-container input:focus {
    border-color: #5cb85c;
    box-shadow: 0 0 10px rgba(92, 184, 92, 0.6);
    }

    /* Search button */
    .search-container button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #394867;
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    }   

    /* Search button hover effect */
    .search-container button:hover {
    background-color: #5cb85c;
    transform: scale(1.05);
    }

    /* CSV download button */
    .search-container .icon-button {
    background-color: #394867;
    padding: 8px 15px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    display: flex;
    transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .search-container .icon-button:hover {
    background-color: #5cb85c;
    transform: scale(1.05);
    }

    .search-container img {
    width: 20px;
    height: 20px;
    }

    </style>

    <script>
        // Function to download table data as CSV
        function downloadCSV() {
            var csv = [];
            var rows = document.querySelectorAll('#adminTable tr');

            rows.forEach((row, rowIndex) => {
                var rowData = [];
                row.querySelectorAll('th, td').forEach((cell, cellIndex) => {
                    if (cellIndex !== 7) {  // Skip the Actions column
                        rowData.push(cell.innerText);
                    }
                });
                csv.push(rowData.join(','));
            });

            var csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
            var downloadLink = document.createElement('a');
            downloadLink.download = 'ListAdmin_siswi.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

    </script>
</head>
<body>

<aside>
  <img src="icons/UptmLogo.png" alt="Logo UPTM" style="margin-left:17px;margin-bottom:25px;margin-top:4px;width:160px;height:75px;">
  <a href="dashboard.php" >DASHBOARD</a>
  <a href="AddStudent.php">ADD STUDENT DATA</a>
  <a>STUDENT LIST</a>
  <a href="admindata.php" class='active' style="margin-left:40px;">ADMIN</a>
  <a href="StudentData.php" style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php">STUDENT REQUEST</a>
  <a href="TrackStud.php" >TRACKING STUDENT</a>
  <a href="faq.php" ></i>FAQ</a>
</aside>

<div class="container">
    <section>
        <h2 class="title">ADMIN LIST</h1>

        
        <div class="search-container">
        <form action="admindata.php" method="GET">
        <input type="text" name="search" placeholder="Search by Name or ID" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <!-- Download CSV Button -->
    <button onclick="downloadCSV()" class="icon-button">
        <img src="icons/csv-file.png" alt="Download CSV">
    </button>
</div>


        <!-- Admin Table -->
        <div class="tbl-header">
            <table id="adminTable" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>PASSWORD</th>
                    <th>ACTIONS</th>
                </tr>

                <!-- Displaying the fetched admin data from the database -->
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td>
    <div class="btn-group" role="group" aria-label="Admin actions">    
        <!-- Edit Icon -->
        <a href="editadmin.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        
        <!-- Delete Icon -->
        <form action="admindata.php" method="POST" class="d-inline">
            <button type="submit" name="delete_admin" value="<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </section>
</div>
<!-- Footer Section -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
</footer>
</body>
</html>
