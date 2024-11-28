<?php 
session_start();
include("database.php");  // Connect to your 'siswi' database

// Check if form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Registration Form (Sign Up) handling
    if (isset($_POST['signup'])) {
        $admin_id = $_POST['id'];
        $admin_email = $_POST['email'];
        $admin_name = $_POST['name'];
        $admin_password = $_POST['pswd'];
        $confirm_password = $_POST['confirm_pswd'];
    
        // Validate form inputs
        if (!empty($admin_email) && !empty($admin_password) && $admin_password === $confirm_password && !is_numeric($admin_email)) {
            // Check for existing ID or email
            $check_query = "SELECT * FROM admin WHERE id = '$admin_id' OR email = '$admin_email'";
            $check_result = mysqli_query($con, $check_query);
    
            if (mysqli_num_rows($check_result) > 0) {
                echo "<script type='text/javascript'>alert('Email or ID already exists.');</script>";
            } else {
                // Insert query to add the admin into the 'admin' table in 'siswi' database
                $query = "INSERT INTO admin (id, email, name, password) VALUES ('$admin_id', '$admin_email', '$admin_name', '$admin_password')";
                if (mysqli_query($con, $query)) {
                    echo "<script type='text/javascript'>alert('Successfully Registered');</script>";
                } else {
                    echo "<script type='text/javascript'>alert('Failed to Register');</script>";
                }
            }
        } else {
            if ($admin_password !== $confirm_password) {
                echo "<script type='text/javascript'>alert('Passwords do not match.');</script>";
            } else {
                echo "<script type='text/javascript'>alert('Please fill in all fields correctly');</script>";
            }
        }
    }
    

    // Login Form handling
    if (isset($_POST['login'])) {
        $admin_email = $_POST['email'];
        $admin_password = $_POST['password'];

        // Validate login inputs
        if (!empty($admin_email) && !empty($admin_password) && !is_numeric($admin_email)) {
            $query = "SELECT * FROM admin WHERE email = '$admin_email' LIMIT 1";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                // Check if the password matches
                if ($user_data['password'] == $admin_password) {
                    // Store session data
                    $_SESSION['admin_id'] = $user_data['id'];

                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit;
                } else {
                    echo "<script type='text/javascript'>alert('Invalid Password');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Invalid Email');</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Please enter valid credentials');</script>";
        }
    }
}
?>


<style>

body{
	margin: 0;
	padding: 0;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	font-family: 'Jost', sans-serif;
	background: linear-gradient(to bottom, #d3d3d3, #e7e7e7,#e7e7e7, #d3d3d3);
    
}

img{
    height: 140px;
    margin-left: 20px;
}
.main{
	width: 350px;
	height: 468px;
	overflow: hidden;
	background: linear-gradient(to bottom, #210d7f, #302b63, #210d7f);
	border-radius: 10px;
	box-shadow: 5px 20px 50px #000;
}
#chk{
	display: none;
}
.signup{
	position: relative;
	width:100%;
	height: 100%;
}
label{
	color: #fff;
	font-size: 2.3em;
	justify-content: center;
	display: flex;
	margin-bottom: 15px;
    margin-left: 50px;
    margin-right:50px;
    margin-top:42px;
	font-weight: bold;
	cursor: pointer;
	transition: .5s ease-in-out;
}
input {
    width: 60%;
    height: 10px;
    background: #e0dede;
    justify-content: center;
    display: flex;
    margin: 11px auto; /* Reduced the margin to make fields closer */
    padding: 12px;
    border: none;
    outline: none;
    border-radius: 5px;
}
button{
	width: 60%;
	height: 40px;
	margin: 10px auto;
	justify-content: center;
	display: block;
	color: #fff;
	background: #c1c3ff;
	font-size: 1em;
	font-weight: bold;
	margin-top: 5px;
	outline: none;
	border: none;
	border-radius: 5px;
	transition: .2s ease-in;
	cursor: pointer;
}
button:hover{
	background: #6d44b8;
}
.login{
	height: 460px;
	background: #eee;
	border-radius: 60% / 10%;
	transform: translateY(-180px);
	transition: .8s ease-in-out;
}
.login label{
	color: #573b8a;
	transform: scale(.6);
}

#chk:checked ~ .login{
	transform: translateY(-500px);
}
#chk:checked ~ .login label{
	transform: scale(1);	
}
#chk:checked ~ .signup label{
	transform: scale(.6);
}

.home-link {
    display: block;
    margin: 10px auto;
    text-align: center;
    font-size: 1em;
    color: #573b8a;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.home-link:hover {
    color: #6d44b8;
    text-decoration: underline;
}

.checkbox-container {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-top: 0px ;
    margin-bottom: 0px ;
    margin-left: 10px ;
    padding:0;
    width: 60%; /* Matches the input field width for consistency */
}

.checkbox-container input[type="checkbox"] {
    cursor: pointer;
}

.checkbox-container a {
    font-size: 0.9em;
    color: #573b8a;
    cursor: pointer;
    user-select: none; /* Prevent text selection when clicking */
    width: 60%;
	height: 40px;
	margin-top: 10px ;
    margin-bottom: 2px ;
    
}

</style>


<script>
function toggleSignupPassword() {
    const password = document.getElementById("signupPassword");
    const confirmPassword = document.getElementById("confirmPassword");
    const showPassword = document.getElementById("signupShowPassword");
    const type = showPassword.checked ? "text" : "password";
    password.type = type;
    confirmPassword.type = type;
}

function toggleLoginPassword() {
    const password = document.getElementById("loginPassword");
    const showPassword = document.getElementById("loginShowPassword");
    password.type = showPassword.checked ? "text" : "password";
}
</script>

<!DOCTYPE html>
<html>
<head>
    <title>SISWI CURFEW TRACKING SYSTEM</title>
    <link rel="icon" type="image/png" href="icons/sictrackslogo.png"/>
    <link rel="stylesheet" type="text/css" href="slide navbar style.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    
</head>
<body>
    <div>
        <img src="icons/UptmLogo.png" alt="">
        <h2>SISWI CURFEW TRACKING SYSTEM</h2>

        <!-- New hyperlink to index.php -->
        <a href="index.php" class="home-link">Go to Scanning Page</a>

        <div class="main">
            <input type="checkbox" id="chk" aria-hidden="true">
            <!-- Sign Up Form -->
            <div class="signup">
    <form method="POST" action="">
        <label for="chk" aria-hidden="true">Sign up</label>
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="id" placeholder="Staff ID" required>
        
        <!-- Password Fields with Show Password -->
        <input type="password" name="pswd" id="signupPassword" placeholder="Password" required>
        <input type="password" name="confirm_pswd" id="confirmPassword" placeholder="Confirm Password" required>
        <div class="checkbox-container">
    <input type="checkbox" id="signupShowPassword" onclick="toggleSignupPassword()">
    <a for="signupShowPassword">Show Password</a>
</div>
        
        <button type="submit" name="signup">Sign up</button>
    </form>
</div>

<div class="login">
    <form method="POST" action="">
        <label for="chk" aria-hidden="true">Login</label>
        <input type="email" name="email" placeholder="Email" required>
        
        <!-- Password Field with Show Password -->
        <input type="password" name="password" id="loginPassword" placeholder="Password" required>
        <div class="checkbox-container">
    <input type="checkbox" id="loginShowPassword" onclick="toggleLoginPassword()">
    <a for="loginShowPassword">Show Password</a>
</div>
        
        <button type="submit" name="login">Login</button>
    </form>
</div>

        </div>
    </div>
</body>