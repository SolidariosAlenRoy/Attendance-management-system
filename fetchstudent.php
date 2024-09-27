<?php
require_once("dbconnection.php");

$selected_section = isset($_POST['section']) ? $_POST['section'] : '';

// Fetch students filtered by section
$query = "
    SELECT s.id, s.student_name, a.date, a.subject, a.time, a.attendance_status 
    FROM students s
    LEFT JOIN attendance a ON s.id = a.student_id
";

if ($selected_section != '') {
    $query .= " WHERE s.section = ?";
}

$query .= " ORDER BY s.id DESC";

$stmt = mysqli_prepare($conn, $query);
if ($selected_section != '') {
    mysqli_stmt_bind_param($stmt, "s", $selected_section);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$output = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>";
        $output .= "<td>".$row['student_name']."</td>";
        $output .= "<td>".$row['date']."</td>";
        $output .= "<td>".$row['subject']."</td>";
        $output .= "<td>".$row['time']."</td>";
        $output .= "<td>".$row['attendance_status']."</td>";
        $output .= "<td>
                        <a href='updatestudent.php?id=".$row['id']."'>Update</a> | 
                        <a href='delete.php?id=".$row['id']."' onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>
                    </td>";
        $output .= "</tr>";
    }
} else {
    $output = "<tr><td colspan='6'>No students found for the selected section.</td></tr>";
}

echo $output;
?>
