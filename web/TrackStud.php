<?php
session_start();
include("database.php");

// Check if the 'not_checked_in' filter is applied
$notCheckedInFilter = isset($_GET['not_checked_in']) && $_GET['not_checked_in'] == '1';
$query = $notCheckedInFilter 
    ? "SELECT * FROM tracking WHERE checkin IS NULL OR checkin = '' ORDER BY trackid DESC" 
    : "SELECT * FROM tracking ORDER BY trackid DESC";
$result = mysqli_query($con, $query);

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
    html,body {
      background: #f5f5ff;
      overflow-x: hidden;
    }

    .container {
      margin-left: 16%; /* Same as the width of the sidenav */
      padding: 20px;
      color: white;
    }

    /* Table Styling */
    .tbl-header {
      background-color: rgba(255, 255, 255, 0.2);
      margin-left: 30px;
      width: 95%;
      border-radius: 10px;
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
      color: #43454a;
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
    td.status-late {
    color: red;
    }

    /* Filter & Download Button Styling */
    .filter-section, .download-section {
      text-align: center;
      margin-bottom: 20px;
      color:black;
    }

    .filter-section input, .filter-section button {
      padding: 8px 12px;
      font-size: 14px;
      margin: 0 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .filter-section button {
      background-color: #394867;
      color: white;
      cursor: pointer;
    }

    .filter-section button:hover {
      background-color: #2c354a;
    }

    .download-section button {
      background-color: #394867;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      color: white;
    }

    .download-section button:hover {
      background-color: #2c354a;
    }

    /* Title Styling */
    .title {
      font-family: "Times New Roman", Times, serif;
      font-size: 45px;
      margin-left: 30px;
      color: black;
      text-align: center;
    }

    /* Responsive Layout */
    @media screen and (max-width: 767px) {
      .row.content { height: auto; } 
    }
  
  </style>

  <script>
    function applyFilters() {
  var filterDate = document.getElementById('filterDate').value;
  var filterID = document.getElementById('filterID').value.toLowerCase();
  var rows = document.querySelectorAll('#studentTable tbody tr');

  rows.forEach(row => {
    var dateCell = row.querySelector('td:nth-child(5)').textContent.trim();
    var idCell = row.querySelector('td:nth-child(1)').textContent.toLowerCase();

    var dateMatch = true;
    var idMatch = true;

    if (filterDate) {
      // Convert both filter date and cell date to a comparable format
      var formattedFilterDate = new Date(filterDate).toDateString();
      var formattedCellDate = new Date(dateCell).toDateString();
      dateMatch = formattedFilterDate === formattedCellDate;
    }

    if (filterID) {
      idMatch = idCell.includes(filterID);
    }

    // Show or hide row based on filters
    row.style.display = dateMatch && idMatch ? '' : 'none';
  });
}

    function downloadCSV() {
      var csv = [];
      var rows = document.querySelectorAll('#studentTable tr');
      
      rows.forEach(row => {
          var rowData = [];
          row.querySelectorAll('th, td').forEach(cell => rowData.push(cell.innerText));
          csv.push(rowData.join(','));
      });

      var csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
      var downloadLink = document.createElement('a');
      downloadLink.download = 'TrackStudentData.csv';
      downloadLink.href = window.URL.createObjectURL(csvFile);
      downloadLink.style.display = 'none';
      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
    }

    function toggleNotCheckedIn() {
    var isChecked = document.getElementById('notCheckedIn').checked;
    if (isChecked) {
        window.location.href = 'TrackStud.php?not_checked_in=1';
    } else {
        window.location.href = 'TrackStud.php';
    }
}

  </script>
</head>
<body>


<aside>
  <img src="icons/UptmLogo.png" alt="Logo UPTM" style="margin-left:17px;margin-bottom:25px;margin-top:4px;width:160px;height:75px;">
  <a href="dashboard.php" >DASHBOARD</a>
  <a href="AddStudent.php">ADD STUDENT DATA</a>
  <a>STUDENT LIST</a>
  <a href="admindata.php" style="margin-left:40px;">ADMIN</a>
  <a href="StudentData.php" style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php">STUDENT REQUEST</a>
  <a href="TrackStud.php" class='active' >TRACKING STUDENT</a>
  <a href="faq.php" ></i>FAQ</a>
</aside>

<div class="container">
  <h2 class="title">TRACKING STUDENT</h2>

  <div class="filter-section">
    <form method="get" action="TrackStud.php">
      <label for="filterDate">Filter by Date:</label>
      <input type="date" id="filterDate" name="filterDate">
      
      <label for="filterID">Filter by Student ID:</label>
      <input type="text" id="filterID" name="filterID" placeholder="Enter Student ID">
      
      <label for="notCheckedIn">Not Checked In:</label>
      <input type="checkbox" id="notCheckedIn" name="not_checked_in" value="1" 
             <?php echo $notCheckedInFilter ? 'checked' : ''; ?> onchange="this.form.submit()">
      
      <button type="button" onclick="applyFilters()">Filter</button>
    </form>
  </div>

  <div class="download-section">
    <button onclick="downloadCSV()">Download CSV</button>
  </div>

  <div class="tbl-header">
    <table id="studentTable" cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>NAME</th>
          <th>CHECK OUT</th>
          <th>CHECK IN</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><a href="viewstudent.php?id=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['checkout']; ?></td>
            <td><?php echo $row['checkin']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td class="<?php echo $row['status'] == 'Late' ? 'status-late' : ''; ?>"><?php echo $row['status']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<footer>
  <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
</footer>
</body>
</html>