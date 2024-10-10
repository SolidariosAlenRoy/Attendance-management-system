<?php
include 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];  // Capture the department from the form
    
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            // Login successful
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['department'] = $department;  // Store the department in the session
            
            header('Location: home.php');  // Redirect to home page
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
    
    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
    <link rel="stylesheet" href="css/log.css">
</head>
<body>
    <div class="container">
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="image-container">
                        <img src="image/ClassTrack.png" alt="Classroom" class="side-image"> 
                    </div>
                </div>
                <div class="flip-card-back">
                    <div class="login-container">
                        <p>Please Log-in</p>
                        <form id="loginForm" method="POST" action="">
                            <div class="input-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="input-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <div class="input-group">
                                <label for="department">Department</label>
                                <select id="department" name="dept" required>
                                    <option value="" disabled selected>Select your Department</option>
                                    <option value="cabecs">CABECS</option>
                                    <option value="chap">CHAP</option>
                                    <option value="coe">COE</option>
                                    <option value="case">CASE</option>
                                    <option value="case">BED</option>
                                </select>
                            </div>
                            <div id="error-message"></div>
                            <button type="submit" class="login-btn">Login</button>
                        </form>
                        <p>Don't have an account?</p>
                        <a href="register.php" class="reg-link">Sign-Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/log.js"></script>
</body>
</html>
