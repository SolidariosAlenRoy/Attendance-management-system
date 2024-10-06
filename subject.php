<?php
require_once("dbconnection.php");

// Handle form submission to add a new subject
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_subject'])) {
    $subject_name = $_POST['subject_name'];

    // Insert new subject into the database
    if (!empty($subject_name)) {
        $insert_query = "INSERT INTO subjects (subject_name) VALUES (?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $subject_name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Get the last inserted subject ID
            $subject_id = mysqli_insert_id($conn);
            
            // Get all students
            $student_query = "SELECT id FROM students";
            $student_result = mysqli_query($conn, $student_query);
            
            // Insert a record for each student for the new subject
            if (mysqli_num_rows($student_result) > 0) {
                while ($student = mysqli_fetch_assoc($student_result)) {
                    $student_id = $student['id'];
                    $insert_student_subject_query = "INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)";
                    $subject_stmt = mysqli_prepare($conn, $insert_student_subject_query);
                    if ($subject_stmt) {
                        mysqli_stmt_bind_param($subject_stmt, 'ii', $student_id, $subject_id); // Change to bind subject_id instead of subject_name
                        mysqli_stmt_execute($subject_stmt);
                        mysqli_stmt_close($subject_stmt);
                    }
                }
            }
            header("Location: subject.php"); // Redirect to prevent form resubmission
            exit();
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    }
}

// Fetch subjects from the database
$subject_query = "SELECT id, subject_name FROM subjects";
$subject_result = mysqli_query($conn, $subject_query);

// Handle deletion of a subject
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // First, delete any associated records in student_subjects
    $delete_student_subjects_query = "DELETE FROM student_subjects WHERE subject_id = ?";
    $stmt = mysqli_prepare($conn, $delete_student_subjects_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $delete_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Then, delete the subject itself
    $delete_query = "DELETE FROM subjects WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $delete_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: subject.php"); // Redirect to prevent further actions on refresh
        exit();
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/studentdata.css">
    <title>Manage Subjects</title>
</head>
<body>
    <div class="container">
        <h1>Manage Subjects</h1>

        <!-- Form to add a new subject -->
        <form method="POST">
            <label for="subject_name">New Subject:</label>
            <input type="text" name="subject_name" id="subject_name" required>
            <button type="submit" name="add_subject">Add Subject</button>
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
                if (mysqli_num_rows($subject_result) > 0) {
                    while ($row = mysqli_fetch_assoc($subject_result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                        echo "<td>
                                <a href='subject.php?delete_id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this subject?')\">Delete</a>
                              </td>";
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
