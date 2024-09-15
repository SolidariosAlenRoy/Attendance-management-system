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
        <form id="add-student-form">
            <label for="student-name">Student Name:</label>
            <input type="text" id="student-name" name="student-name" required>

            <label for="student-section">Section:</label>
            <input type="text" id="student-section" name="student-section" required>

            <button type="submit">Add Student</button>
        </form>

        <a href="studentdata.php" class="back-link">Back to Student Attendance</a>
    </div>
</body>
</html>
