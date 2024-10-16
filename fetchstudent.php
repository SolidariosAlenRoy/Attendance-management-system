<?php
require_once("dbconnection.php");

$selected_section = isset($_POST['section']) ? $_POST['section'] : '';
$selected_subject = isset($_POST['subject']) ? $_POST['subject'] : '';

// Debugging: Check the selected values
error_log("Selected Section: " . $selected_section);
error_log("Selected Subject: " . $selected_subject);

// Fetch sections for the dropdown
$section_query = "SELECT DISTINCT section FROM students";
$section_result = mysqli_query($conn, $section_query);

// Fetch students filtered by section and subject
$query = "
     SELECT s.id AS student_id, s.student_name, a.date, sub.id AS subject_id, sub.subject_name AS subject, a.time, a.attendance_status 
    FROM students s
    LEFT JOIN attendance a ON s.id = a.student_id
    LEFT JOIN subjects sub ON a.subject_id = sub.id
";

$conditions = [];
$params = [];

// Log incoming data for debugging purposes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    file_put_contents('log.txt', print_r($_POST, true)); // Log the incoming data
}

// Add conditions based on the selected section
if (!empty($selected_section) && $selected_section != 'select_section') {
    $conditions[] = "s.section = ?";
    $params[] = $selected_section; 
}

// Add conditions based on the selected subject
if (!empty($selected_subject)) {
    $conditions[] = "sub.subject_name = ?";
    $params[] = $selected_subject; 
}

// If there are conditions, append them to the query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// For debugging: Output the constructed query
error_log("Constructed Query: " . $query);

$query .= " ORDER BY s.id DESC";

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $output = '';
if (mysqli_num_rows($result) == 0) {
    $output = "<tr><td colspan='6'>No students found for the selected filters.</td></tr>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $formattedTime = !empty($row['time']) ? date('H:i:s', strtotime($row['time'])) : '';

        $output .= "<tr>";
        $output .= "<td>" . htmlspecialchars($row['student_name']) . "</td>";
        $output .= "<td class='date'>" . htmlspecialchars($row['date']) . "</td>";
        
        // Now 'subject_id' should be available
        if (!empty($row['subject_id'])) {
            $output .= "<td class='subject' data-subject-id='" . htmlspecialchars($row['subject_id']) . "'>" . htmlspecialchars($row['subject']) . "</td>";
        } else {
            $output .= "<td class='subject'>No subject assigned</td>";
        }
        
        $output .= "<td class='time'>" . htmlspecialchars($formattedTime) . "</td>";
        $output .= "<td>
                        <select class='attendance-status' data-student-id='" . $row['student_id'] . "'>
                            <option value=''>Select</option>
                            <option value='Present' " . ($row['attendance_status'] == 'Present' ? 'selected' : '') . ">Present</option>
                            <option value='Absent' " . ($row['attendance_status'] == 'Absent' ? 'selected' : '') . ">Absent</option>
                            <option value='Late' " . ($row['attendance_status'] == 'Late' ? 'selected' : '') . ">Late</option>
                        </select>
                    </td>";
        $output .= "<td>
                        <div class='btn-container'>
                            <a class='update-btn' href='updatestudent.php?id=" . $row['student_id'] . "'>Update</a> | 
                            <a class='delete-btn' href='delete.php?id=" . $row['student_id'] . "' onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a> 
                        </div>
                    </td>";
        $output .= "</tr>";
    }
}

    echo $output; 
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('change', '.attendance-status', function() {
    var studentId = $(this).data('student-id');
    var selectedStatus = $(this).val();
    
    // Logic to update attendance status in the database can go here
    console.log("Student ID: " + studentId + ", Selected Status: " + selectedStatus);
});

// JavaScript to handle subject dropdown
$(document).on('change', 'subject', function() {
    var selectedSubject = $(this).find('option:selected').text();  // Get the selected subject text
    
    // Find the parent row of the current dropdown
    var parentRow = $(this).closest('tr'); 

    // Find the subject cell within the same row
    var subjectCell = parentRow.find('subject');

    // Update the subject text for the current row
    subjectCell.text(selectedSubject);
});
</script>

