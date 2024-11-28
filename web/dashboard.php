<?php
session_start();
include("database.php");

$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 'Guest';

// Query to fetch tracking data
$query = "SELECT * FROM tracking ORDER BY trackid DESC";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Your table rows
    }
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}

// Query to get the total number of students
$total_students_query = "SELECT COUNT(*) as total FROM student";
$total_students_result = mysqli_query($con, $total_students_query);
$total_students_data = mysqli_fetch_assoc($total_students_result);
$total_students = $total_students_data['total'];

// Query to get the total number of requests
$total_requests_query = "SELECT COUNT(*) as total FROM request";
$total_requests_result = mysqli_query($con, $total_requests_query);
$total_requests_data = mysqli_fetch_assoc($total_requests_result);
$total_requests = $total_requests_data['total'];

// Query to fetch daily check-in and check-out counts
$daily_query = "
    SELECT DATE(checkin) as date, 
           COUNT(checkin) as daily_checkins, 
           COUNT(checkout) as daily_checkouts 
    FROM tracking 
    GROUP BY DATE(checkin)";
$daily_result = mysqli_query($con, $daily_query);

// Query to get On Time vs Late counts
$status_query = "
    SELECT status, COUNT(*) as count
    FROM tracking
    WHERE status IN ('On Time', 'Late')
    GROUP BY status";
$status_result = mysqli_query($con, $status_query);

$onTimeCount = 0;
$lateCount = 0;

while ($row = mysqli_fetch_assoc($status_result)) {
    if ($row['status'] == 'On Time') {
        $onTimeCount = $row['count'];
    } elseif ($row['status'] == 'Late') {
        $lateCount = $row['count'];
    }
}

// Query to fetch reasons for requests
$reason_query = "
    SELECT reason, COUNT(*) as count
    FROM request
    GROUP BY reason";
$reason_result = mysqli_query($con, $reason_query);

$reasons = [];
$counts = [];

while ($row = mysqli_fetch_assoc($reason_result)) {
    $reasons[] = $row['reason'];
    $counts[] = $row['count'];
}

// Query to get total check-ins and check-outs
$totals_query = "
    SELECT 
        SUM(CASE WHEN checkin IS NOT NULL THEN 1 ELSE 0 END) as total_checkins,
        SUM(CASE WHEN checkout IS NOT NULL THEN 1 ELSE 0 END) as total_checkouts
    FROM tracking";
$totals_result = mysqli_query($con, $totals_query);
$totals_data = mysqli_fetch_assoc($totals_result);

$total_checkins = $totals_data['total_checkins'];
$total_checkouts = $totals_data['total_checkouts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SISWI Curfew Tracking System</title>
    <link rel="icon" type="image/png" href="icons/sictrackslogo.png" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="sidebar.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    

    <style>
        /* Styles */
        body {
            background: #f5f5ff;
            margin-top: 24px;
            overflow: hidden;
            margin-bottom: 0px;
        }

        header {
            position: fixed;
            top: 0;
            width: 98%;
            background-color: #020154;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1;
        }
        header h2 {
            margin-bottom: 0.5%;
            margin-top: 0.5%;
            margin-left: 16%;
            text-align: center;
        }

        header a.logout {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            padding: 8px 12px;
            background-color: #ff6347;
            border-radius: 5px;
        }

        .dashboard-content {
            margin-left: 15%;
            padding-top: 55px;
            padding-right: 25px;
            padding-bottom: 4%;
            padding-left: 25px;
        }

        /* Individual Stats Boxes */
        .stats-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .stats-box {
            background-color: #ffffff;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 50%;
            margin-bottom: 10px;
        }
        .stats-box h3 {
            font-size: 18px;
            color: #333333;
            margin-bottom: 10px;
        }
        .stats-box p {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .chart-grid {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            gap: 1px;
        }
        .chart-grid h3 {
            margin-top:0;
        }
        
        .chart-box {
            background-color: white;
            padding: 16px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 345px; /* Adjust width to fit all charts in one line */
            margin-top: 10px;
            height: 315px;
            place-items: center;    
        }
        .chart-box1 {
            background-color: white;
            padding: 16px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 455px; /* Adjust width to fit all charts in one line */
            margin-top: 10px;
            height: 318px;
        }
        
        #activityChart, #statusOverviewChart, #checkInCheckOutPieChart {
            width: 80% !important;
            height: 85% !important;

        }

        

        @media print {
        /* Hide elements that are not needed for printing */
        .print-button, aside, header, footer {
        display: none; /* Hide these elements */
        
        }

        /* Ensure the dashboard content takes full width in print */
        .dashboard-content {
        width: 100%;
        margin: 0;
        padding: 0;
        page-break-after: always; /* Ensure sections start on a new page */
        }

        /* Avoid breaking charts across pages */
        .chart-box {
        page-break-inside: avoid; /* Avoid breaking charts across pages */
        margin-bottom: 20px; /* Add spacing between charts */
        }

        /* Force a page break after each chart grid */
        .chart-grid {
        page-break-after: always; /* Start each chart on a new page */
        }
        }
    </style>
</head>
<body>
    <header>
        <h2>SISWI Curfew Tracking System</h2>
        <div>
            <span>Welcome, User ID: <?php echo $admin_id; ?></span>
            <a href="login.php" class="logout">Logout</a>
        </div>
    </header>

    <aside>
  <img src="icons/UptmLogo.png" alt="Logo UPTM" style="margin-left:17px;margin-bottom:25px;margin-top:4px;width:160px;height:75px;">
  <a href="dashboard.php" class='active'>DASHBOARD</a>
  <a href="AddStudent.php">ADD STUDENT DATA</a>
  <a>STUDENT LIST</a>
  <a href="admindata.php" style="margin-left:40px;">ADMIN</a>
  <a href="StudentData.php" style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php">STUDENT REQUEST</a>
  <a href="TrackStud.php" >TRACKING STUDENT</a>
  <a href="faq.php" ></i>FAQ</a>
</aside>

    <div class="dashboard-content">
        <button onclick="printReport()" class="print-button" >Print Report</button>

       <!-- Separate Stats Boxes -->
       <div class="stats-container">
            <div class="stats-box">
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>
            <div class="stats-box">
                <h3>Total Requests</h3>
                <p><?php echo $total_requests; ?></p>
            </div>
        </div>


        <div class="chart-grid">
            <!-- Check-ins vs Check-outs Pie Chart -->
            <div class="chart-box">
                <h3>Daily Check-in/Check-out Trends</h3>
                <canvas id="checkInCheckOutPieChart"></canvas>
            </div>

            <div class="chart-box1">
                <h3>Request Reasons Count</h3>
                <canvas id="reasonBarChart" width="250" height="155"></canvas>
            </div>

            <div class="chart-box">
                <h3>Status Overview: On Time vs Late</h3>
                <canvas id="statusOverviewChart" ></canvas>
            </div>

            
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> SISWI Curfew Tracking System. All rights reserved.</p>
    </footer>

    <script>
        // Data for charts
        var totalCheckins = <?php echo json_encode($total_checkins); ?>;
        var totalCheckouts = <?php echo json_encode($total_checkouts); ?>;
        var onTimeCount = <?php echo json_encode($onTimeCount); ?>;
        var lateCount = <?php echo json_encode($lateCount); ?>;
        var reasons = <?php echo json_encode($reasons); ?>;
        var counts = <?php echo json_encode($counts); ?>;

        // Check-ins vs Check-outs Pie Chart
        new Chart(document.getElementById('checkInCheckOutPieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Check-ins', 'Check-outs'],
                datasets: [{
                    data: [totalCheckins, totalCheckouts],
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            }
        });

        // Status Overview Chart
        new Chart(document.getElementById('statusOverviewChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['On Time', 'Late'],
                datasets: [{
                    data: [onTimeCount, lateCount],
                    backgroundColor: ['#4CAF50', '#b82323']
                }]
            }
        });

        // Request Reasons Bar Chart
        new Chart(document.getElementById('reasonBarChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: reasons,
                datasets: [{
                    data: counts,
                    backgroundColor: '#4BC0C0'
                }]
            }
        });

        function printReport() {
            setTimeout(() => { window.print(); }, 100);
        }
    </script>
</body>
</html>
