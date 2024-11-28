<?php

    session_start();

        include("database.php");

        $query = "SELECT * FROM tracking ORDER BY trackid DESC";
        $result = mysqli_query($con, $query);
        date_default_timezone_set('Asia/Kuala_Lumpur');

?>
<!DOCTYPE html>
<html>
<head>
<title>SISWI CURFEW TRACKING SYSTEM</title>
<link rel="icon" type="image/png" href="icons/sictrackslogo.png"/>
<link rel="stylesheet" type="text/css" href="sidebar.css" />

<style>
    /* CSS styles for the layout */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        background: linear-gradient(141deg, rgba(25,32,150,1) 0%, rgba(255,255,255,1) 51%, rgba(252,69,69,1) 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .main {
        width: 350px;
        height: 145px;
        padding: 18px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 5px 20px 50px rgba(0, 0, 0, 0.3);
        text-align: center;
        margin-bottom: 14px;
    }
    h2{
        font-size: 30px;
        margin-bottom: 14px;
    }
    .main2 {
        width: 1250px;
        height: 398px;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        box-shadow: 5px 20px 50px rgba(0, 0, 0, 0.3);
        text-align: center;
        margin-bottom: 20px;
    }
    .main h1 {
        margin-bottom: 10px;
        color: #333;
        margin-top: 4px;
        font-size: 25px;
    }
    .main input[type="text"] {
        width: 97%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }
    .title{
        margin-bottom: 13px;
        margin-top: 10px;
    }
    .login-link {
        margin-top: 15px;
        display: block;
        font-size: 14px;
        color: #333;
        text-decoration: none;
    }
    .container {
        width: 550px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .tbl-header table {
        width: 100%;
        border-collapse: collapse;
    }
    .tbl-header th, .tbl-header td {
        padding: 7px;
        border: 1px solid #ddd;
        text-align: left;
        
    }
    .tbl-container {
        max-height: 340px; /* Adjust height as needed */
        overflow-y: scroll;
        border: 1px solid #ddd;
        margin-top: 8px;
    }
    .tbl-container table {
        width: 100%;
    }

    th, td {
     padding: 15px; /* Add more padding for readability */
     text-align: left;
     vertical-align: middle;
     font-weight: 300;
     font-size: 14px; /* Increase font size */
     border-bottom: solid 1px rgba(255, 255, 255, 0.1);
    border: 1px solid black;
    position: sticky;
      }
    th {
    background-color: #283D81;
    color: white;
    position: sticky;
    top: 0; /* Stick the header to the top of the container */
    z-index: 1;
    }
    td {
    background-color: rgba(15, 57, 196, 0.1); /* Slightly transparent background for better contrast */
    }
    tr:nth-child(even) td {
    background-color: rgba(15, 57, 196, 0.2); /* Alternate row colors */
    }
    tr:hover td {
    background-color: rgba(15, 57, 196, 0.3); /* Highlight row on hover */
    }   
    td.status-late {
    color: red;
}

</style>

<script>
    function submitStudentID(id) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "insertTracking.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
                loadTrackingData();
                document.getElementById("fname").value = "";
            }
        };
        xhr.send("id=" + id);
    }

    function onIDInputChange() {
        var id = document.getElementById("fname").value;
        if (id) {
            submitStudentID(id);
        }
    }

    function loadTrackingData() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "loadTrackingData.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("trackingTable").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    window.onload = function() {
        document.getElementById("fname").focus();
        loadTrackingData();
        setInterval(loadTrackingData, 5000); // Refresh every 5 seconds
    };
</script>
</head>
<body>
<div class="container">
<h2>SISWI CURFEW TRACKING SYSTEM</h2>
    <div class="main">
        <h1>Track Student</h1>
        <label for="fname">Student ID</label>
        <input type="text" id="fname" name="fname" placeholder="Enter Student ID" onchange="onIDInputChange()" required>
        <a href="login.php" class="login-link">Staff Login</a>
    </div>

    <div class="main2">
        <section>
            <h1 class="title">LIST STUDENT DATA</h1>
            <div class="tbl-container">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>CHECK OUT</th>
                        <th>CHECK IN</th>
                        <th>Date</th>
                        <th>Status</th> 
                    </tr>
                    <tbody id="trackingTable"></tbody> <!-- AJAX content will be loaded here -->
                </table>
            </div>
        </section>
    </div>
</div>

</body>
</html>