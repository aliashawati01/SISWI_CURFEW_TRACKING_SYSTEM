<?php 
session_start();
include("database.php");

// Initialize the search and filter parameters
$search_query = "";
if (isset($_GET['search']) || isset($_GET['semester']) || isset($_GET['no_rum'])) {
    $search_term = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
    $semester = isset($_GET['semester']) ? mysqli_real_escape_string($con, $_GET['semester']) : '';
    $no_rum = isset($_GET['no_rum']) ? mysqli_real_escape_string($con, $_GET['no_rum']) : '';

    // Build dynamic search query
    $search_query = " WHERE (id LIKE '%$search_term%' OR name LIKE '%$search_term%' OR ic LIKE '%$search_term%' OR sem LIKE '%$search_term%')";

    // Add additional filters
    if ($semester) $search_query .= " AND sem = '$semester'";
    if ($no_rum) $search_query .= " AND rumah = '$no_rum'";
}

// Sort column and direction
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_direction = isset($_GET['dir']) && $_GET['dir'] == 'desc' ? 'DESC' : 'ASC';

// Fetch filtered and sorted student data from the database
$query = "SELECT * FROM student $search_query ORDER BY $sort_column $sort_direction";
$result = mysqli_query($con, $query);
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

    /* Styling for sortable headers */
.sortable-header a {
    color: white;           /* Keep the text color white */
    text-decoration: none;   /* Remove underline */
}

/* Hover effect to make it clear that headers are clickable */
.sortable-header a:hover {
    color: #ccc;             /* Optional: Light gray on hover for clarity */
    text-decoration: none;
}


    </style>

    <script>
        // Function to download table data as CSV
        function downloadCSV() {
            var csv = [];
            var rows = document.querySelectorAll('#studentTable tr');

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
            downloadLink.download = 'ListStudent_siswi.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
        // JavaScript function for table sorting
        function sortTable(columnIndex) {
            let table = document.getElementById("studentTable");
            let rows = Array.from(table.rows).slice(1); // Skip header row

            rows.sort((rowA, rowB) => {
                let cellA = rowA.cells[columnIndex].innerText.toLowerCase();
                let cellB = rowB.cells[columnIndex].innerText.toLowerCase();
                return cellA.localeCompare(cellB);
            });

            rows.forEach(row => table.appendChild(row)); // Append sorted rows back to table
        }

    </script>
</head>
<body>

<aside>
  <img src="icons/UptmLogo.png" alt="Logo UPTM" style="margin-left:17px;margin-bottom:25px;margin-top:4px;width:160px;height:75px;">
  <a href="dashboard.php" >DASHBOARD</a>
  <a href="AddStudent.php" >ADD STUDENT DATA</a>
  <a>STUDENT LIST</a>
  <a href="admindata.php" style="margin-left:40px;">ADMIN</a>
  <a href="StudentData.php" class='active' style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php">STUDENT REQUEST</a>
  <a href="TrackStud.php" >TRACKING STUDENT</a>
  <a href="faq.php" ></i>FAQ</a>
</aside>

<div class="container">
    <section>
        <h2 class="title">STUDENT LIST</h1>

        
        <div class="search-container">
    <form action="StudentData.php" method="GET">
        <input type="text" name="search" placeholder="Search by name, ID, IC, or semester" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        
        <select name="semester">
            <option value="">Select Semester</option>
            <option value="1" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '1') ? 'selected' : ''; ?>>1</option>
            <option value="2" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '2') ? 'selected' : ''; ?>>2</option>
            <option value="3" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '3') ? 'selected' : ''; ?>>3</option>
            <option value="4" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '4') ? 'selected' : ''; ?>>4</option>
            <option value="5" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '5') ? 'selected' : ''; ?>>5</option>
            <option value="6" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '6') ? 'selected' : ''; ?>>6</option>
            <option value="7" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '7') ? 'selected' : ''; ?>>7</option>
            <option value="8" <?php echo (isset($_GET['semester']) && $_GET['semester'] == '8') ? 'selected' : ''; ?>>8</option>
        </select>

        <select name="no_rum">
            <option value="">Select House No</option>
            <option value="A-1-1" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-1') ? 'selected' : ''; ?>>A-1-1</option>
            <option value="A-1-2" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-2') ? 'selected' : ''; ?>>A-1-2</option>
            <option value="A-1-3" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-3') ? 'selected' : ''; ?>>A-1-3</option>
            <option value="A-1-4" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-4') ? 'selected' : ''; ?>>A-1-4</option>
            <option value="A-1-5" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-5') ? 'selected' : ''; ?>>A-1-5</option>
            <option value="A-1-6" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-6') ? 'selected' : ''; ?>>A-1-6</option>
            <option value="A-1-7" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-7') ? 'selected' : ''; ?>>A-1-7</option>
            <option value="A-1-8" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-8') ? 'selected' : ''; ?>>A-1-8</option>
            <option value="A-1-9" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-9') ? 'selected' : ''; ?>>A-1-9</option>
            <option value="A-1-10" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-10') ? 'selected' : ''; ?>>A-1-10</option>
            <option value="A-1-11" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-11') ? 'selected' : ''; ?>>A-1-11</option>
            <option value="A-1-12" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-12') ? 'selected' : ''; ?>>A-1-12</option>
            <option value="A-1-13" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-13') ? 'selected' : ''; ?>>A-1-13</option>
            <option value="A-1-14" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-1-14') ? 'selected' : ''; ?>>A-1-14</option>
            <option value="A-2-1" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-1') ? 'selected' : ''; ?>>A-2-1</option>
            <option value="A-2-2" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-2') ? 'selected' : ''; ?>>A-2-2</option>
            <option value="A-2-3" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-3') ? 'selected' : ''; ?>>A-2-3</option>
            <option value="A-2-4" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-4') ? 'selected' : ''; ?>>A-2-4</option>
            <option value="A-2-5" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-5') ? 'selected' : ''; ?>>A-2-5</option>
            <option value="A-2-6" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-6') ? 'selected' : ''; ?>>A-2-6</option>
            <option value="A-2-7" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-7') ? 'selected' : ''; ?>>A-2-7</option>
            <option value="A-2-8" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-8') ? 'selected' : ''; ?>>A-2-8</option>
            <option value="A-2-9" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-9') ? 'selected' : ''; ?>>A-2-9</option>
            <option value="A-2-10" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-10') ? 'selected' : ''; ?>>A-2-10</option>
            <option value="A-2-11" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-11') ? 'selected' : ''; ?>>A-2-11</option>
            <option value="A-2-12" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-12') ? 'selected' : ''; ?>>A-2-12</option>
            <option value="A-2-13" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-13') ? 'selected' : ''; ?>>A-2-13</option>
            <option value="A-2-14" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-2-14') ? 'selected' : ''; ?>>A-2-14</option>
            <option value="A-3-1" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-1') ? 'selected' : ''; ?>>A-3-1</option>
            <option value="A-3-2" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-2') ? 'selected' : ''; ?>>A-3-2</option>
            <option value="A-3-3" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-3') ? 'selected' : ''; ?>>A-3-3</option>
            <option value="A-3-4" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-4') ? 'selected' : ''; ?>>A-3-4</option>
            <option value="A-3-5" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-5') ? 'selected' : ''; ?>>A-3-5</option>
            <option value="A-3-6" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-6') ? 'selected' : ''; ?>>A-3-6</option>
            <option value="A-3-7" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-7') ? 'selected' : ''; ?>>A-3-7</option>
            <option value="A-3-8" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-8') ? 'selected' : ''; ?>>A-3-8</option>
            <option value="A-3-9" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-9') ? 'selected' : ''; ?>>A-3-9</option>
            <option value="A-3-10" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-10') ? 'selected' : ''; ?>>A-3-10</option>
            <option value="A-3-11" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-11') ? 'selected' : ''; ?>>A-3-11</option>
            <option value="A-3-12" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-12') ? 'selected' : ''; ?>>A-3-12</option>
            <option value="A-3-13" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-13') ? 'selected' : ''; ?>>A-3-13</option>
            <option value="A-3-14" <?php echo (isset($_GET['no_rum']) && $_GET['no_rum'] == 'A-3-14') ? 'selected' : ''; ?>>A-3-14</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>

 
    <button class="icon-button" onclick="downloadCSV()">
        <img src="icons/csv-file.png" alt="Download CSV"> Download CSV
    </button>


    </form>
</div>

<!-- Student Table with Sortable Columns -->
<div class="tbl-header">
    <table id="studentTable">
        <tr>
        <th class="sortable-header">
            <a href="?sort=id&dir=<?php echo ($sort_column == 'id' && $sort_direction == 'ASC') ? 'desc' : 'asc'; ?>">ID <i class="fa fa-sort"></i></a>
        </th>
        <th class="sortable-header">
            <a href="?sort=name&dir=<?php echo ($sort_column == 'name' && $sort_direction == 'ASC') ? 'desc' : 'asc'; ?>">NAME <i class="fa fa-sort"></i></a>
        </th>
        <th>EMAIL</th>
        <th class="sortable-header">
            <a href="?sort=sem&dir=<?php echo ($sort_column == 'sem' && $sort_direction == 'ASC') ? 'desc' : 'asc'; ?>">SEMESTER <i class="fa fa-sort"></i></a>
        </th>
        <th>NO IC</th>
        <th>NO TELEFON</th>
        <th class="sortable-header">
            <a href="?sort=rumah&dir=<?php echo ($sort_column == 'rumah' && $sort_direction == 'ASC') ? 'desc' : 'asc'; ?>">NO ROOM <i class="fa fa-sort"></i></a>
        </th>
        <th>ACTIONS</th>
        </tr>

        <!-- Displaying the fetched student data from the database -->
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['sem']; ?></td>
                <td><?php echo $row['ic']; ?></td>
                <td><?php echo $row['notel']; ?></td>
                <td><?php echo $row['rumah']; ?></td>
                <td>
                    <a href="viewStudent.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                    <a href="editStudent.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                    <form action="code.php" method="POST" style="display:inline;">
                        <button type="submit" name="delete_student" value="<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    </form>
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
