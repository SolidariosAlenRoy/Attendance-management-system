<?php
require_once("dbConnection.php");

// Database connection
$conn = new mysqli('localhost', 'root', '', 'students');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch attendance data (this will run on page load, not just on form submission)
$sql = "SELECT s.student_name, a.date, a.subject, a.time, a.attendance_status
        FROM students s
        JOIN attendance a ON s.id = a.student_id";
$result = $conn->query($sql);

// Function to download CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_report.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Student Name', 'Date', 'Subject', 'Time', 'Attendance Status']);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit();

    // Function to delete a record and fill the gap
function deleteAndFillGap($id) {
    global $conn;

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Delete the record
        $deleteSql = "DELETE FROM students WHERE id = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Step 2: Update subsequent records to fill the gap
        $updateSql = "UPDATE students SET id = id - 1 WHERE id > ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();
        echo "Record deleted and gap filled successfully.";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Call the function with the ID of the record you want to delete
deleteAndFillGap(4); // For example, to delete record with ID 4
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="css/attendancereport.css">
</head>
<body>
    <div class="container">
        <h1>Attendance Report</h1>

        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Subject</th>
                    <th>Time</th>
                    <th>Attendance Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Removed the POST condition here, so it runs on page load
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['attendance_status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No attendance records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Button to download attendance report as CSV -->
        <form method="POST" action="">
            <button type="submit" name="download">Save Attendance Report</button>
        </form>

        <!-- Back link to studentdata.php -->
        <a href="studentdata.php" class="back-link">Back to Student Attendance</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
