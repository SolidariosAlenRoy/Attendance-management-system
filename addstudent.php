<?php
require_once("dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student-name'];
    $section = $_POST['student-section'];
    $guardian_email = $_POST['guardian-email'];
    $guardian_phone = $_POST['guardian-phone'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO students (student_name, section, guardian_email, guardian_phone) VALUES (?, ?, ?, ?)");
    
    // Bind parameters
    $stmt->bind_param("ssss", $student_name, $section, $guardian_email, $guardian_phone);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to studentdata.php after successfully adding the student
        header("Location: classlist.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
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
            </form>

            
        </div>
    </body>
    </html>
