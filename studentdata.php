<?php
require_once("dbConnection.php");

// Fetch distinct sections from the students table
$section_query = "SELECT DISTINCT section FROM students";
$section_result = mysqli_query($conn, $section_query);

// Fetch subjects for the dropdown
$subject_query = "SELECT id, subject_name FROM subjects";
$subject_result = mysqli_query($conn, $subject_query);

// Retrieve selected filters from POST
$selected_section = isset($_POST['section']) ? $_POST['section'] : '';
$selected_subject = isset($_POST['subject']) ? $_POST['subject'] : '';

// Prepare the base query for fetching students
// Prepare the base query for fetching students
$query = "
    SELECT s.id AS student_id, s.student_name, a.date, sub.id AS subject_id, sub.subject_name AS subject, a.time, a.attendance_status 
    FROM students s
   
    LEFT JOIN subjects sub ON a.subject_id = sub.id
";

// Initialize conditions and params arrays
$conditions = [];
$params = [];

// Debugging output for selected filters
echo "Selected Section: " . $selected_section . "<br>";
echo "Selected Subject: " . $selected_subject . "<br>";

// Filter by selected section
if (!empty($selected_section) && $selected_section != 'All Sections') {
    $conditions[] = "s.section = ?";
    $params[] = $selected_section;
}

// Filter by selected subject
if (!empty($selected_subject)) {
    $conditions[] = "sub.id = ?";
    $params[] = $selected_subject;
}

// Append conditions to the base query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}
$query .= " ORDER BY s.id DESC";

// Prepare the statement
$stmt = mysqli_prepare($conn, $query);

// Bind parameters dynamically
if (!empty($params)) {
    $types = str_repeat("s", count($params)); // Adjust based on actual data types
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

// Execute the statement
if (!mysqli_stmt_execute($stmt)) {
    echo "Execution Error: " . mysqli_error($conn); // Log execution errors
}
$result = mysqli_stmt_get_result($stmt);




// Initialize output variable
$output = '';
if (mysqli_num_rows($result) == 0) {
    // Debugging output
echo "Selected Section: " . htmlspecialchars($selected_section) . "<br>";
echo "Selected Subject: " . htmlspecialchars($selected_subject) . "<br>";

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
    var selectedSubject = $('#subject-select').val(); // Ensure you're fetching the right subject ID
    console.log("Fetching students with Section: " + selectedSection + ", Subject: " + selectedSubject); // Add this line
    $.ajax({
        type: 'POST',
        url: 'fetchstudent.php',
        data: { section: selectedSection, subject: selectedSubject },
        success: function(data) {
            console.log("Response Data: " + data); // Log the response data
            $('#student-table-body').html(data);
        }
    });
}



    $('#section').change(function() {
        fetchStudents(); // Fetch data on change
    });

    $('#subject-select').change(function() {
        fetchStudents(); // Fetch data when subject changes
    });

        // Update subject in the table when subject dropdown changes
        // Update subject in the table when subject dropdown changes
        $(document).ready(function() {
    $('#subject-select').on('change', function() {
        var selectedSubject = $(this).val();

        // Show all rows initially
        $('tbody tr').show();

        // If a subject is selected, hide rows that do not match
        if (selectedSubject) {
            $('tbody tr').filter(function() {
                return $(this).find('.subject').text() !== selectedSubject;
            }).hide();
        }
    });
});


        $(document).on('change', '.attendance-status', function() {
            var studentId = $(this).data('student-id');
            var attendanceStatus = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'updateAttendance.php',
                data: { id: studentId, status: attendanceStatus },
                success: function(response) {
                    console.log('Attendance updated:', response);
                }
            });
        });

        // Update current date and time dynamically
        setInterval(function() {
            const now = new Date();
            const formattedDate = now.toLocaleDateString();
            const formattedTime = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            $('.date').text(formattedDate);
            $('.time').text(formattedTime);
        }, 1000);
    });
    </script>


</head>
<body>
<nav>
<div class="logo-name">
            <div class="logo-image">
               <img src="image/classtrack.png" alt="">
            </div>

            <span class="logo_name">CLASS TRACK</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="home.php">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Home</span>
                </a></li>
                <li><a href="attendancereport.php">
                    <i class="uil uil-clipboard"></i>
                    <span class="link-name">Attendance Report</span>
                </a></li>
                <li><a href="savedraft.php">
                <i class="uil uil-check-circle"></i>
                    <span class="link-name">Attendance Submit</span>
                </a></li>
                <li><a href="subject.php">
                <i class="uil uil-subject"></i>
                    <span class="link-name">Add Subject</span>
                </a></li>
            </ul>
            
            <ul class="logout-mode">
                <li><a href="login.php">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a></li>

                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                    <span class="link-name">Dark Mode</span>
                </a>

                <div class="mode-toggle">
                  <span class="switch"></span>
                </div>
            </li>
            </ul>
        </div>
    </nav>


<section class="dashboard">
    <div class="container">
        <form method="POST" id="filters-form" class="controls">
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
<select name="subject" id="subject-select">
    <option value="">Select a subject</option>
    <?php 
    if (mysqli_num_rows($subject_result) > 0) {
        while ($row = mysqli_fetch_assoc($subject_result)) {
            echo "<option value='".$row['id']."' ".($selected_subject == $row['id'] ? "selected" : "").">".$row['subject_name']."</option>";
        }
    } else {
        echo "<option value=''>No Subjects Available</option>";
    }
    ?>
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
                <?= $output ?>
            </tbody>
        </table>
        
        <div class="actions-container">
            <!-- Save Draft Button -->
            <button class="save-draft" onclick="window.location.href='savedraft.php';">Save Draft</button>

            <!-- Attendance Report Button -->
            <form method="POST" action="attendancereport.php">
                <input type="hidden" name="section" value="<?= htmlspecialchars($selected_section) ?>">
                <input type="hidden" name="subject_id" value="<?= htmlspecialchars($selected_subject) ?>">
                <button type="submit" class="attendance-report">Attendance Report</button>
            </form>
        </div>
    </div>
</section>
</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
