<?php
include 'dbconnection.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $institution = $_POST['institution'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, institution) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $institution);

        if ($stmt->execute()) {

            header('Location: login.php'); // Redirect to the login page
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Attendance Management System - Register</title>
  <link rel="stylesheet" href="css/reg.css">
</head>
<body>
  <div class="container">
    <div class="login-container">
      <p>Create an Account</p>
      
      <?php if (!empty($error_message)): ?>
        <div id="error-message" style="color:red; text-align:center;"><?php echo $error_message; ?></div>
      <?php endif; ?>

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
          <label for="institution">Institution</label>
          <input type="text" id="institution" name="institution" placeholder="Type your Institution" required>
        </div>

        <button type="submit" class="login-btn">Register</button>
      </form>
    </div>
  </div>
  <script src="js/script.js"></script>
</body>
</html>
