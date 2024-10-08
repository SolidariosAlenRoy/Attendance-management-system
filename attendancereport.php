<?php
require_once("dbConnection.php");

// Retrieve selected filters from POST
$selected_section = isset($_POST['section']) ? $_POST['section'] : '';
$selected_subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : '';

// Prepare the base query for fetching attendance data
$query = "
    SELECT s.student_name, a.date, sub.subject_name AS subject, a.time, a.attendance_status 
    FROM students s
    LEFT JOIN attendance a ON s.id = a.student_id
    LEFT JOIN subjects sub ON a.subject_id = sub.id
";

// Add conditions if necessary (like filtering by section or subject)


// Initialize conditions and params arrays
$conditions = [];
$params = [];

// Filter by section if selected
if (!empty($selected_section) && $selected_section != 'All Sections') {
    $conditions[] = "s.section = ?";
    $params[] = $selected_section;
}

// Filter by subject if selected
if (!empty($selected_subject_id)) {
    $conditions[] = "sub.id = ?";
    $params[] = $selected_subject_id;
}

// Append conditions to the base query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Order by student ID
$query .= " ORDER BY s.id DESC";

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);

// Bind parameters dynamically
if (!empty($params)) {
    $types = str_repeat("s", count($params)); // Assuming all params are strings; adjust types as necessary
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

// Execute the statement
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Generate the attendance report
$output = '';
if (mysqli_num_rows($result) == 0) {
    $output = "<tr><td colspan='6'>No attendance records found for the selected filters.</td></tr>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $formattedTime = !empty($row['time']) ? date('H:i:s', strtotime($row['time'])) : '';

        $output .= "<tr>";
        $output .= "<td>" . htmlspecialchars($row['student_name']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['date']) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['subject']) . "</td>"; // Display subject name
        $output .= "<td>" . htmlspecialchars($formattedTime) . "</td>";
        $output .= "<td>" . htmlspecialchars($row['attendance_status']) . "</td>";
        $output .= "</tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="css/report.css">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Date</th>
                <th>Subject</th> <!-- Display subject name in the report -->
                <th>Time</th>
                <th>Attendance Status</th>
            </tr>
        </thead>
        <tbody>
            <?= $output ?>
        </tbody>
    </table>
</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
