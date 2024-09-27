<?php

require_once("dbConnection.php");


// Database connection
$conn = new mysqli('localhost', 'root', '', 'student');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch absent students
$sql = "SELECT s.student_name, s.guardian_email, s.guardian_phone
        FROM students s
        JOIN attendance a ON s.student_id = a.student_id
        WHERE a.status = 'Absent'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Name: " . $row['student_name'] . " - Email: " . $row['guardian_email'] . " - Phone: " . $row['guardian_phone'] . "<br>";
    }
} else {
    echo "No absent students.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Draft</title>
    <link rel="stylesheet" href="css/savedraft.css">
</head>
<body>
    <div class="container">
        <h1>Submit Attendance</h1>
        <div class="students-list">
            <h2>Student List</h2>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Guardian's Email</th>
                        <th>Guardian's Phone Number</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="form-group">
            <button onclick="generateEmails()">Generate Email Drafts</button>
        </div>
    </div>

    <script src="js/savedraft.js"></script>
</body>
</html>