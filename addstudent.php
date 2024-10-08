<?php
require_once("dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student-name'];
    $section = $_POST['student-section'];
    $guardian_email = $_POST['guardian-email'];
    $guardian_phone = $_POST['guardian-phone'];

    // Prepare the SQL statement for inserting into the 'students' table
    $stmt = $conn->prepare("INSERT INTO students (student_name, section, guardian_email, guardian_phone) VALUES (?, ?, ?, ?)");
    
    // Bind parameters and execute
    $stmt->bind_param("ssss", $student_name, $section, $guardian_email, $guardian_phone);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id; // Get last inserted student ID

        // Insert into the 'attendance' table (use NULL or a default value for subject for now)
        $stmt_attendance = $conn->prepare("INSERT INTO attendance (student_id, date, time, attendance_status) VALUES (?, CURDATE(), CURTIME(), 'Select')");
        $stmt_attendance->bind_param("i", $last_id);

        if ($stmt_attendance->execute()) {
            header("Location: classlist.php"); // Redirect upon success
            exit();
        } else {
            echo "Error adding attendance record: " . $stmt_attendance->error;
        }

        $stmt_attendance->close(); // Close attendance statement

    } else {
        echo "Error adding student: " . $stmt->error;
    }

    $stmt->close(); // Close student insert statement
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="css/addstudent.css">
</head>
<body>
    <div class="container">
        <h1>Add Student</h1>
        <form id="add-student-form" action="addstudent.php" method="POST">
            <label for="student-name">Student Name:</label>
            <input type="text" id="student-name" name="student-name" required>

            <label for="student-section">Section:</label>
            <input type="text" id="student-section" name="student-section" required>

            <label for="guardian-email">Guardian Email:</label>
            <input type="email" id="guardian-email" name="guardian-email" required>

            <label for="guardian-phone">Guardian Contact Number:</label>
            <input type="text" id="guardian-phone" name="guardian-phone" required>

            <button type="submit">Add Student</button>
            <a href="classlist.php" class="back-link">Back to Class List</a>
        </form>
    </div>
</body>
</html>
