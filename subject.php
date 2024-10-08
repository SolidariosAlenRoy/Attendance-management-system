<?php
require_once("dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_name = $_POST['subject_name'];

    // Prepare the SQL statement for inserting into the 'subjects' table
    $stmt = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
    
    // Bind parameters and execute
    $stmt->bind_param("s", $subject_name);

    if ($stmt->execute()) {
        $subject_id = $conn->insert_id; // Get the last inserted subject ID

        // Insert attendance record for each student for the new subject
        $student_query = "SELECT id FROM students";
        $student_result = $conn->query($student_query);

        if ($student_result->num_rows > 0) {
            $attendance_stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, date, time, attendance_status) VALUES (?, ?, CURDATE(), CURTIME(), 'Not Marked')");
            
            while ($student = $student_result->fetch_assoc()) {
                $student_id = $student['id'];
                $attendance_stmt->bind_param("ii", $student_id, $subject_id);
                $attendance_stmt->execute();
            }

            $attendance_stmt->close();
        }

        header("Location: subject.php"); // Redirect upon success
        exit();
    } else {
        echo "Error adding subject: " . $stmt->error;
    }

    $stmt->close(); // Close subject insert statement
}

// Fetch subjects from the database
$subject_query = "SELECT id, subject_name FROM subjects";
$subject_result = $conn->query($subject_query);

// Handle deletion of a subject
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete associated records in the attendance table first
    $stmt = $conn->prepare("DELETE FROM attendance WHERE subject_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Then delete the subject itself
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: subject.php"); // Redirect to prevent further actions on refresh
        exit();
    } else {
        echo "Error deleting subject: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/studentdata.css">
    <title>Manage Subjects</title>
</head>
<body>
    <div class="container">
        <h1>Manage Subjects</h1>

        <form method="POST" action="subject.php">
            <label for="subject_name">New Subject:</label>
            <input type="text" name="subject_name" id="subject_name" required>
            <button type="submit">Add Subject</button>
            <a href="home.php" class="back-link">Back to Home</a>
        </form>

        <h2>Existing Subjects</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display subjects
                if ($subject_result->num_rows > 0) {
                    while ($row = $subject_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                        echo "<td><a href='subject.php?delete_id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this subject?')\">Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No subjects found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
