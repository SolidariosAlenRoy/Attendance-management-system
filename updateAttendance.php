<?php
require_once("dbconnection.php");

// Check if the required POST variables are set
if (isset($_POST['id']) && isset($_POST['status'])) {
    $studentId = $_POST['id'];
    $attendanceStatus = $_POST['status'];

    // Prepare and execute the update statement
    $query = "UPDATE attendance SET attendance_status = ? WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "si", $attendanceStatus, $studentId); // "si" means string and integer types
    
    if (mysqli_stmt_execute($stmt)) {
        // Success response
        echo json_encode(['success' => true, 'message' => 'Attendance status updated successfully.']);
    } else {
        // Error response
        echo json_encode(['success' => false, 'message' => 'Failed to update attendance status.']);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Error response for missing data
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

// Close the database connection
mysqli_close($conn);
?>
