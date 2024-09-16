<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Attendance Management System - Register</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <!-- Left side for the image -->
    <div class="image-container">
      <img src="ClassTrack.png" alt="Classroom" class="side-image"> <!-- Replace 'ClassTrack.png' with the actual image path -->
    </div>

    <!-- Right side for the registration form -->
    <div class="login-container">
      <p>Create an Account</p>

      <form id="registerForm" method="POST" action="">
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter a username" required>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter a password" required>
        </div>
        <div class="input-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
        </div>
        <div class="input-group">
          <label for="confirm_password">Institution</label>
          <input type="institution" id="institution" name="institution" placeholder="Type your Institution" required>
        </div>

        <div id="error-message"></div>
        <button class="login-btn" onclick="window.location.href='login.php?id=1';">Register</button>
      </form>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>
