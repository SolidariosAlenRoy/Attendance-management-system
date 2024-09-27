<?php
require_once("dbconnection.php"); // Include the database connection

// Initialize variables
$student = null;

// Check if the student ID is provided in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch student data based on the student ID
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id); // 'i' for integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result); // Fetch the student data
    } else {
        die("Student not found.");
    }
    $stmt->close();
}

// Update the student information when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student-id'];  // Hidden input for student ID
    $student_name = $_POST['student-name'];
    $section = $_POST['student-section'];
    $guardian_email = $_POST['guardian-email'];
    $guardian_phone = $_POST['guardian-phone'];

    // Prepare the SQL statement to update student information
    $stmt = $conn->prepare("UPDATE students SET student_name = ?, section = ?, guardian_email = ?, guardian_phone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $student_name, $section, $guardian_email, $guardian_phone, $student_id);

    if ($stmt->execute()) {
        // Redirect back to studentdata.php after successful update
        header("Location: classlist.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="css/updatestudent.css">
</head>
<body>
    <div class="container">
        <h1>Update Student</h1>

        <!-- Pre-populate form with current student data -->
        <form id="update-student-form" method="POST" action="updatestudent.php?id=<?php echo $student['id']; ?>">
            <input type="hidden" id="student-id" name="student-id" value="<?php echo $student['id']; ?>" required>

            <label for="student-name">Student Name:</label>
            <input type="text" id="student-name" name="student-name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>

            <label for="student-section">Section:</label>
            <input type="text" id="student-section" name="student-section" value="<?php echo htmlspecialchars($student['section']); ?>" required>

            <label for="guardian-email">Guardian Email:</label>
            <input type="email" id="guardian-email" name="guardian-email" value="<?php echo htmlspecialchars($student['guardian_email']); ?>" required>

            <label for="guardian-phone">Guardian Phone:</label>
            <input type="text" id="guardian-phone" name="guardian-phone" value="<?php echo htmlspecialchars($student['guardian_phone']); ?>" required>

            <button type="submit">Update Student</button>
        </form>

        <a href="studentdata.php" class="back-link">Back to Student Attendance</a>
    </div>
</body>
</html>
