<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Attendance Management System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <!-- Left side for the image -->
    <div class="image-container">
      <img src="ClassTrack.png" alt="Classroom" class="side-image"> <!-- Replace 'ClassTrack.png' with the actual image path -->
    </div>

    <!-- Right side for the login form -->
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
          <select id="dept" name="dept" required>
            <option value="" disabled selected>Select your Department</option>
            <option value="cabecs">CABECS</option>
            <option value="chap">CHAP</option>
            <option value="coe">COE</option>
            <option value="case">CASE</option>
          </select>
        </div>

        <div class="input-group remember-me">
          <input type="checkbox" id="rememberMe" name="rememberMe">
          <label for="rememberMe">Remember Me</label>
        </div>

        <div id="error-message"></div>
        <button type="submit" class="login-btn">Login</button>
      </form>
      <p>Don't have an account?</p>
      <a href="register.php" class="reg-link">Sign-Up</a>
    </div>
  </div>
  <script src="script.js"></script>
</body>
</html>
