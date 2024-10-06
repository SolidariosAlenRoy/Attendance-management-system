<?php
require_once("dbConnection.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'students');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch attendance data
$query = "
    SELECT s.id, s.student_name, a.date, sub.subject_name AS subject, a.time, a.attendance_status 
    FROM students s
    LEFT JOIN attendance a ON s.id = a.student_id
    LEFT JOIN subjects sub ON a.subject_id = sub.id
    ORDER BY s.id DESC
";
$result = $conn->query($query); // Correctly using the query variable

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Function to download CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_report.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Student Name', 'Date', 'Subject', 'Time', 'Attendance Status']);
    
    // Reset the result pointer to fetch data again for the CSV download
    $result->data_seek(0); 

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['student_name'] ?? '',
                $row['date'] ?? '',
                $row['subject'] ?? '',
                $row['time'] ?? '',
                $row['attendance_status'] ?? ''
            ]);
        }
    }
    fclose($output);
    exit(); // Ensure that the script stops after outputting CSV
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
                // Display attendance records
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['student_name'] ?? '') . "</td>"; 
                        echo "<td>" . htmlspecialchars($row['date'] ?? '') . "</td>";         
                        echo "<td>" . htmlspecialchars($row['subject'] ?? '') . "</td>"; // Updated default value
                        echo "<td>" . htmlspecialchars($row['time'] ?? '') . "</td>";         
                        echo "<td>" . htmlspecialchars($row['attendance_status'] ?? '') . "</td>"; 
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
$conn->close(); // Close the database connection
?>
