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
    <style>
        /* General styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

/* Form styling */
form {
    margin-bottom: 20px;
    text-align: center;
}

label {
    font-weight: bold;
    margin-right: 10px;
}

select {
    padding: 5px 10px;
    font-size: 16px;
}

button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    margin: 10px;
}

button:hover {
    background-color: #0056b3;
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background-color: #007bff;
    color: white;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
}

.back-link:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }

    button, .back-link {
        font-size: 14px;
        padding: 8px 15px;
    }

    .container {
        padding: 10px;
    }
}

    </style>
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
