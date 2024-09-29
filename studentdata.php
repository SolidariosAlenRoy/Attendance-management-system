<?php
require_once("dbconnection.php");

$selected_section = isset($_POST['section']) ? $_POST['section'] : '';
$selected_subject = isset($_POST['subject']) ? $_POST['subject'] : '';

// Fetch sections for the dropdown
$section_query = "SELECT DISTINCT section FROM students";
$section_result = mysqli_query($conn, $section_query);

// Fetch students filtered by section and subject
$query = "
    SELECT s.id, s.student_name, a.date, a.subject, a.time, a.attendance_status 
    FROM students s
    LEFT JOIN attendance a ON s.id = a.student_id
";

$conditions = [];
$params = [];

if ($selected_section != '' && $selected_section != 'select_section') {
    $conditions[] = "s.section = ?";
    $params[] = $selected_section;
}

if ($selected_subject != '') {
    $conditions[] = "a.subject = ?";
    $params[] = $selected_subject;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY s.id DESC";

$stmt = mysqli_prepare($conn, $query);
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
        // Ensure time is formatted correctly
        $formattedTime = date('H:i:s', strtotime($row['time'])); // Format time here
        $output .= "<tr>";
        $output .= "<td>" . htmlspecialchars($row['student_name']) . "</td>";
        $output .= "<td class='date'>" . htmlspecialchars($row['date']) . "</td>";
        $output .= "<td class='subject'>" . htmlspecialchars($row['subject'] ?? '') . "</td>";
        $output .= "<td class='time'>" . htmlspecialchars($formattedTime) . "</td>"; // Display formatted time
        $output .= "<td>
                        <select class='attendance-status' data-student-id='" . $row['id'] . "'>
                            <option value=''>Select</option>
                            <option value='Present' " . ($row['attendance_status'] == 'Present' ? 'selected' : '') . ">Present</option>
                            <option value='Absent' " . ($row['attendance_status'] == 'Absent' ? 'selected' : '') . ">Absent</option>
                            <option value='Late' " . ($row['attendance_status'] == 'Late' ? 'selected' : '') . ">Late</option>
                        </select>
                    </td>";
        $output .= "<td>
                        <div class='btn-container'>
                            <a class='update-btn' href='updatestudent.php?id=" . $row['id'] . "'>Update</a> | 
                            <a class='delete-btn' href='delete.php?id=" . $row['id'] . "' onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a> 
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
            function fetchStudents() {
                var selectedSection = $('#section').val();
                var selectedSubject = $('#subject').val();
                $.ajax({
                    type: 'POST',
                    url: 'fetchstudent.php',
                    data: {section: selectedSection, subject: selectedSubject},
                    success: function(data) {
                        $('#student-table-body').html(data);
                    }
                });            }

            // Fetch students based on selected section or subject
            $('#section, #subject').change(function() {
                fetchStudents();
                // When a subject is changed, update the subject in the table
                var newSubject = $('#subject').val();
                $('.subject').text(newSubject);
            });

            // Update attendance status
            $(document).on('change', '.attendance-status', function() {
                var studentId = $(this).data('student-id');
                var attendanceStatus = $(this).val();
                // Here you can implement an AJAX call to save the attendance status
                $.ajax({
                    type: 'POST',
                    url: 'updateAttendance.php', // Your update attendance PHP file
                    data: {id: studentId, status: attendanceStatus},
                    success: function(response) {
                        console.log('Attendance updated:', response);
                    }
                });
            });

            // Update current date and time in real-time
            function updateDateTime() {
                const now = new Date();
                const formattedDate = now.toLocaleDateString(); // Format as desired
                const formattedTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); // Format time correctly
                $('.date').text(formattedDate);
                $('.time').text(formattedTime);
            }

            setInterval(updateDateTime, 1000); // Update every second

            // Update sidebar state based on localStorage
            const sidebarToggle = document.getElementById('sidebar-toggle');
            sidebarToggle.addEventListener('click', () => {
                const sidebar = document.querySelector("nav");
                const dashboard = document.querySelector(".dashboard");

                sidebar.classList.toggle("close");
                dashboard.classList.toggle("full-width");

                localStorage.setItem("status", sidebar.classList.contains("close") ? "close" : "open");
            });

            // Restore sidebar state
            if (localStorage.getItem("status") === "close") {
                $("nav").addClass("close");
                $(".dashboard").addClass("full-width");
            }
        });
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

            <label for="subject">Subject:</label>
            <select name="subject" id="subject">
                <option value="">All Subjects</option>
                <option value="Math" <?= $selected_subject == 'Math' ? 'selected' : '' ?>>Math</option>
                <option value="Science" <?= $selected_subject == 'Science' ? 'selected' : '' ?>>Science</option>
                <option value="English" <?= $selected_subject == 'English' ? 'selected' : '' ?>>English</option>
            </select>
        </form>

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
    <button class="save-draft" onclick="window.location.href='savedraft.php';">Send Email</button>
    <button class="attendance-report" onclick="window.location.href='attendancereport.php';">Attendance Report</button>
    </form>
</div>
</div>
</section>
</body>
</html>
