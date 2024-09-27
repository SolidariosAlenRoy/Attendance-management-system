<?php
// Include the database connection file
require_once("dbconnection.php");

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE SQL query for attendance
    $query = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);

    // Check if the query preparation was successful
    if ($stmt) {
        // Bind the 'id' parameter to the query
        $stmt->bind_param("i", $id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the main attendance page after successful deletion
            header("Location: studentdata.php");
            exit();
        } else {
            // Display an error message if the query execution fails
            echo "Error executing delete query: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Display an error message if the query preparation fails
        echo "Error preparing the delete query: " . $conn->error;
    }
} else {
    // If 'id' parameter is not present, redirect back to the main attendance page
    header("Location: classlist.php");
    exit();
}

// Close the database connection
$conn->close();
?>
