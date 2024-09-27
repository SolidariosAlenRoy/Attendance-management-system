<?php
require_once("dbconnection.php");

$selected_section = isset($_POST['section']) ? $_POST['section'] : '';

// Fetch sections for the dropdown
$section_query = "SELECT DISTINCT section FROM students";
$section_result = mysqli_query($conn, $section_query);

// Fetch students filtered by section
$query = "
    SELECT s.id, s.student_name, a.date, a.subject, a.time, a.attendance_status 
    FROM students s
    LEFT JOIN attendance a ON s.id = a.student_id
";

if ($selected_section != '' && $selected_section != 'select_section') {
    $query .= " WHERE s.section = ?";
}

$query .= " ORDER BY s.id DESC";

$stmt = mysqli_prepare($conn, $query);
if ($selected_section != '' && $selected_section != 'select_section') {
    mysqli_stmt_bind_param($stmt, "s", $selected_section);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$output = '';
if (mysqli_num_rows($result) == 0) {
    $output = "<tr><td colspan='6'>No students found for the selected section.</td></tr>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>";
        $output .= "<td>".$row['student_name']."</td>";
        $output .= "<td>".$row['date']."</td>";
        $output .= "<td>".$row['subject']."</td>";
        $output .= "<td>".$row['time']."</td>";
        $output .= "<td>".$row['attendance_status']."</td>";
        $output .= "<td>
                        <div class='btn-container'>
                            <a class='update-btn' href='updatestudent.php?id=".$row['id']."'>Update</a> | 
                            <a class='delete-btn' href='delete.php?id=".$row['id']."' onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a> | 
                        </div>
                    </td>";
        $output .= "</tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/studentdata.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Student Attendance</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#section').change(function() {
                var selectedSection = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: 'fetchstudent.php',
                    data: {section: selectedSection},
                    success: function(data) {
                        $('#student-table-body').html(data);
                    }
                });

                // Restore sidebar state
                if (localStorage.getItem("status") === "close") {
                    $("nav").addClass("close");
                    $(".dashboard").addClass("full-width");
                }
            });
        });

        function goToAttendanceReport() {
            var selectedSection = $('#section').val();
            window.location.href = 'attendancereport.php?section=' + selectedSection;
        }
    </script>
</head>
<body>
<nav>
    <div class="logo-name">
        <div class="logo-image">
            <img src="image/classtrack.png" alt="Class Track Logo">
        </div>
        <span class="logo_name">CLASS TRACK</span>
    </div>

    <div class="sidebar-toggle" id="sidebar-toggle">
        <i class="uil uil-bars"></i>
    </div>

    <div class="menu-items">
        <ul class="nav-links">
            <li><a href="home.php"><i class="uil uil-estate"></i><span class="link-name">Home</span></a></li>
            <li><a href="studentdata.php"><i class="uil uil-user"></i><span class="link-name">Student Attendance</span></a></li>
            <li><a href="attendancereport.php"><i class="uil uil-clipboard"></i><span class="link-name">Attendance Report</span></a></li>
            <li><a href="savedraft.php"><i class="uil uil-check-circle"></i><span class="link-name">Attendance Submit</span></a></li>
        </ul>
        <ul class="logout-mode">
            <li><a href="login.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
        </ul>
    </div>
</nav>

<section class="dashboard">
    <div class="container">
        <form method="POST" class="controls">
            <label for="section">Section:</label>
            <select name="section" id="section">
                <option value="">All Sections</option>
                <?php 
                if (mysqli_num_rows($section_result) > 0) {
                    while ($row = mysqli_fetch_assoc($section_result)) {
                        echo "<option value='".$row['section']."' ".($selected_section == $row['section'] ? "selected" : "").">".$row['section']."</option>";
                    }
                } else {
                    echo "<option value=''>No Sections Available</option>";
                }
                ?>
            </select>
        </form>

        <h1>Student Attendance</h1>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Subject</th>
                    <th>Time</th>
                    <th>Attendance Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="student-table-body">
                <?= $output; ?>
            </tbody>
        </table>
        <div class="actions-container">
            <button class="save-draft" onclick="window.location.href='savedraft.php';">Save Draft</button>
            <button class="attendance-report" onclick="window.location.href='attendancereport.php?section=' + $('#section').val();">Attendance Report</button>

        </div>
    </div>
</section>
<script src="js/studentdata.js"></script>
</body>
</html>
