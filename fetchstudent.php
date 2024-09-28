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

// Fetch subjects for the dropdown
$subject_query = "SELECT DISTINCT subject FROM attendance";
$subject_result = mysqli_query($conn, $subject_query);
$subjects = [];
while ($sub_row = mysqli_fetch_assoc($subject_result)) {
    $subjects[] = $sub_row['subject'];
}

$output = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format time in 12-hour format
        $formattedTime = date('h:i A', strtotime($row['time'])); 
        $output .= "<tr>";
        $output .= "<td>" . htmlspecialchars($row['student_name']) . "</td>";
        $output .= "<td class='date'></td>"; // Date cell to be set by JavaScript
        $output .= "<td class='subject'>" . htmlspecialchars($row['subject'] ?? '') . "</td>"; // Display the selected subject
        $output .= "<td class='time'>" . htmlspecialchars($formattedTime) . "</td>"; // Set the formatted time directly
        $output .= "<td class='status'>
                        <select class='status-dropdown' data-initial-status='" . htmlspecialchars($row['attendance_status'] ?? '') . "'>
                            <option value=''>Select Status</option>
                            <option value='Present' " . ($row['attendance_status'] === 'Present' ? 'selected' : '') . ">Present</option>
                            <option value='Absent' " . ($row['attendance_status'] === 'Absent' ? 'selected' : '') . ">Absent</option>
                            <option value='Late' " . ($row['attendance_status'] === 'Late' ? 'selected' : '') . ">Late</option>
                        </select>
                    </td>";
        $output .= "<td>
                        <a href='updatestudent.php?id=" . $row['id'] . "'>Update</a> | 
                        <a href='delete.php?id=" . $row['id'] . "' onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>
                    </td>";
        $output .= "</tr>";
    }
} else {
    $output = "<tr><td colspan='6'>No students found for the selected section.</td></tr>";
}

echo $output;
?>


<script>
    $(document).ready(function() {
    // Function to update the real-time date and time
    function updateDateTime() {
        let currentDate = new Date();
        let date = currentDate.toLocaleDateString();
        let time = formatTime(currentDate);

        // Set date for each row (only if necessary, since date is not fetched from DB)
        $('.date').text(date);
    }

    // Call updateDateTime on page load
    updateDateTime();

    // Update date every second (time is fetched from DB)
    setInterval(updateDateTime, 1000);

    // Handle subject selection change
    $('#subject-dropdown').change(function() {
        const selectedSubject = $(this).val();

        // Update the subject for all students in the table
        $('.subject').each(function() {
            $(this).text(selectedSubject); // Update each subject cell to the selected subject
        });
    });

    // Handle status change
    $(document).on('change', '.status-dropdown', function() {
        const selectedStatus = $(this).val();
        // Optionally, update the initial status for reference
        $(this).data('initial-status', selectedStatus); 
    });

    // Function to format time in 24-hour format
    function formatTime(date) {
        let hours = date.getHours();
        let minutes = date.getMinutes();
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`; // Format time
    }
});

</script>
