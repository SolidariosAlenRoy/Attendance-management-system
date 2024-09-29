<?php
require_once("dbconnection.php");

$selected_section = isset($_POST['section']) ? $_POST['section'] : '';

// Fetch all sections for the dropdown
$section_query = "SELECT DISTINCT section FROM students";
$section_result = mysqli_query($conn, $section_query);

// Fetch students based on selected section
$query = "SELECT * FROM students";
if ($selected_section != '') {
    $query .= " WHERE section = ?";
}
$query .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $query);
if ($selected_section != '') {
    mysqli_stmt_bind_param($stmt, "s", $selected_section);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class List</title>
</head>
<body>
    <div class="container">
        <h1>Class List</h1>
        <form method="POST" action="classlist.php">
            <label for="section">Select Section:</label>
            <select name="section" id="section" onchange="this.form.submit()">
                <option value="">All Sections</option>
                <?php while ($row = mysqli_fetch_assoc($section_result)) {
                    echo "<option value='".$row['section']."' ".($selected_section == $row['section'] ? "selected" : "").">".$row['section']."</option>";
                } ?>
            </select>
        </form>
        <button onclick="window.location.href='addstudent.php';">Add Student</button>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Section</th>
                    <th>Guardian Email</th>
                    <th>Guardian Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) {
                    while ($res = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$res['student_name']."</td>";
                        echo "<td>".$res['section']."</td>";
                        echo "<td>".$res['guardian_email']."</td>";
                        echo "<td>".$res['guardian_phone']."</td>";
                        echo "<td>
                                <a href=\"updatestudent.php?id=".$res['id']."\">Update</a> | 
                                <a href=\"delete.php?id=".$res['id']."\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No students found for the selected section.</td></tr>";
                } ?>
            </tbody>
        </table>
        <a href="studentdata.php" class="back-link">Back to Student Attendance</a>
    </div>
</body>
</html>
