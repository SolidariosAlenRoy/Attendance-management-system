<?php
// Include the database connection file
require_once("dbconnection.php");

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $idToDelete = (int)$_GET['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Prepare the DELETE SQL query for attendance records
        $deleteAttendanceQuery = "DELETE FROM attendance WHERE student_id = ?";
        $attendanceStmt = $conn->prepare($deleteAttendanceQuery);

        // Check if the query preparation was successful
        if ($attendanceStmt) {
            // Bind the 'id' parameter to the query
            $attendanceStmt->bind_param("i", $idToDelete);
            $attendanceStmt->execute();
            $attendanceStmt->close();
        }

        // Step 2: Prepare the DELETE SQL query for students
        $deleteQuery = "DELETE FROM students WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);

        // Check if the query preparation was successful
        if ($deleteStmt) {
            // Bind the 'id' parameter to the query
            $deleteStmt->bind_param("i", $idToDelete);

            // Execute the query
            if ($deleteStmt->execute()) {
                // Close the prepared statement for deletion
                $deleteStmt->close();

                // Step 3: Reassign IDs for remaining records
                $resetIdQuery = "SET @count = 0; UPDATE students SET id = (@count := @count + 1) WHERE id IS NOT NULL;";
                $conn->query($resetIdQuery);
                
                // Step 4: Reset the auto-increment value
                $conn->query("ALTER TABLE students AUTO_INCREMENT = 1;");

                // Commit the transaction
                $conn->commit();

                // Redirect to the main attendance page after successful deletion
                header("Location: studentdata.php");
                exit();
            } else {
                // Display an error message if the query execution fails
                echo "Error executing delete query: " . $deleteStmt->error;
            }
        } else {
            // Display an error message if the query preparation fails
            echo "Error preparing the delete query: " . $conn->error;
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    // If 'id' parameter is not present, redirect back to the main attendance page
    header("Location: classlist.php");
    exit();
}

// Close the database connection
$conn->close();
?>
