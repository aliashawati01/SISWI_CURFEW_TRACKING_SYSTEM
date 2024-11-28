
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ - SISWI Curfew Tracking System Admin</title>
    <link rel="icon" type="image/png" href="icons/sictrackslogo.png" />
    <link rel="stylesheet" type="text/css" href="sidebar.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5ff;

        }
        
        .container {
            margin-left: 28%;
            max-width: 50%;
            padding: 20px;
            background-color: transparent;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .faq-item {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .question {
            font-weight: bold;
            color: #444;
            cursor: pointer;
        }

        .answer {
            margin-top: 8px;
            color: #666;
            line-height: 1.6;
            display: none; /* Hide answer initially */
            padding: 5px 0;
        }

        .title {
      font-family: "Times New Roman", Times, serif;
      font-size: 45px;
      color: black;
      text-align: center;
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
  <a href="StudentData.php" style="margin-left:40px;">STUDENT</a>
  <a href="StudReq.php">STUDENT REQUEST</a>
  <a href="TrackStud.php" >TRACKING STUDENT</a>
  <a href="faq.php" class='active'></i>FAQ</a>
</aside>


    <h2 class="title">Frequently Asked Questions (FAQ)</h2>
    <div class="container">
    <div class="faq-item">
        <div class="question"> Q. How do I add a new student to the system?</div>
        <div class="answer"><p>ANSWER</P>
        <p>1. Navigate to the "Add Student Data" Page Log in to the admin panel.
        <p> In the sidebar, find and click on the "Add Student Data" link. This will take you to the student registration form page.</p>
        <p>2. Fill Out the Student Registration Form</p>
        <p>Fill All Student Information</p>
        <p>3. Set Additional Information (If Applicable)</p>
        <p>Fill Parent Information </p>
        <p>4. Review the Information Entered</p>
        <p>Double-check all details to make sure they are correct.</p>
        <p>Make sure the Student ID and contact details are accurate to avoid any tracking errors.</p>
        <p>5. Submit the Form</p>
        <p>Once all details are filled out and reviewed, click the Submit or Add Student button at the bottom of the form.</p>
        <p>After submission, a success message should appear, confirming the student has been added to the system.</p>
        <p>6. Confirm the Student Was Added Successfully</p>
        <p>To verify, go to the "Student List" page in the admin panel.</p>
        <p>Search for the new student by their ID or name to ensure their details are now listed.</p></div>
         </div>

    <div class="faq-item">
        <div class="question">Q. How can I view the list of students?</div>
        <div class="answer"><p>ANSWER</P>
        <p>1.Navigate to the "Student List" Page:</P>
        <p>In the admin panel, click on "Student List" in the sidebar. This will open a page displaying all students currently registered in the system.</P>
        <p>2.Review Student Details:</P>
        <p>The page includes a table with each student’s information, such as Student ID, Name, and Contact Information.</P>
        <p>3.Edit or Delete Information:</P>
        <p>If needed, you can use the Edit or Delete options beside each student entry to update their information or remove them from the list.</P></div>
    </div>

    <div class="faq-item">
        <div class="question">Q. How do I track student check-ins and check-outs?</div>
        <div class="answer"><p>ANSWER</P>
        <p>1.Access the "Tracking Student" Page:</P>
        <p>In the admin panel, navigate to the sidebar and click on the "Tracking Student" link. This will take you to a page displaying real-time activity logs.</P>
        <p>2.View Recent Activity:</P>
        <p>On this page, you’ll see a table listing each student’s latest check-in and check-out times.</P>
        <p>The table includes columns for the Student ID, Name, Check-Out Time, Check-In Time, Date, and Status.</P>
        <p>3.Check the Status of Each Entry:</P>
        <p>For each entry, the Status column indicates whether the student was "On Time" or "Late" based on the curfew schedule.
        <p>This status helps admins quickly identify any students who may have missed curfew.</P>
        <p>4.Filter or Search for Specific Students (if available):</P>
        <p>Some systems may include a search or filter feature at the top of the page, allowing you to locate specific students or focus on certain dates.</P>
        <p>Use the search bar to enter a student’s ID or name to view their latest activities.</P></div>
    </div>

    <div class="faq-item">
        <div class="question">Q. What does the status "Late" mean?</div>
        <div class="answer">The "Late" status alerts administrators that a student has checked in or out after the designated curfew time set by the institution. This status helps quickly identify students who are not adhering to curfew regulations, making it easier to follow up if needed. Curfew times can be configured in the **settings** page to ensure they align with your institution’s rules and can be adjusted as necessary.</div>
    </div>

    <div class="faq-item">
        <div class="question">Q. How can I handle a student's request for curfew extension?</div>
        <div class="answer">Requests for curfew extensions can be managed on the "Student Requests" page. Here, you can view, approve, or reject extension requests submitted by students.</div>
    </div>

    <div class="faq-item">
        <div class="question">Q. How do I generate reports?</div>
        <div class="answer">You can generate reports by clicking the "Print Report" button on the dashboard. This will include check-in/check-out records, statuses, and request summaries. You can also save the report as a PDF.</div>
    </div>


    <div class="faq-item">
        <div class="question">Q. How do I log out of the admin panel?</div>
        <div class="answer">To log out, click on the "Logout" link at the top of the page or in the sidebar. This will end your session and return you to the login page.</div>
    </div>

</div>

<script>
    // JavaScript to handle the FAQ toggle functionality
    const questions = document.querySelectorAll('.faq-item .question');
    
    questions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling; // The corresponding answer
            // Toggle the visibility of the answer
            if (answer.style.display === "block") {
                answer.style.display = "none";
            } else {
                answer.style.display = "block";
            }
        });
    });
</script>

</body>
</html>
