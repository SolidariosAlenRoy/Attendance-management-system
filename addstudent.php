<?php
require_once("dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student-name'];
    $section = $_POST['student-section'];
    $guardian_email = $_POST['guardian-email'];
    $guardian_phone = $_POST['guardian-phone'];
    $subjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];

    // Insert student into 'students' table
    $stmt = $conn->prepare("INSERT INTO students (student_name, section, guardian_email, guardian_phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $student_name, $section, $guardian_email, $guardian_phone);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id; // Get last inserted student ID

        // Insert into 'student_subjects' table
        $stmt_subject = $conn->prepare("INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)");
        
        foreach ($subjects as $subject_id) {
            echo "Inserting student ID: " . $last_id . " with subject ID: " . $subject_id . "<br>"; // Debugging output
            $stmt_subject->bind_param("ii", $last_id, $subject_id);
            if (!$stmt_subject->execute()) {
                echo "Error inserting subject record: " . $stmt_subject->error . "<br>";
            }

            // Insert into the 'attendance' table with subject_id
            $stmt_attendance = $conn->prepare("INSERT INTO attendance (student_id, subject_id, date, time, attendance_status) VALUES (?, ?, CURDATE(), CURTIME(), 'Select')");
            $stmt_attendance->bind_param("ii", $last_id, $subject_id);
            if (!$stmt_attendance->execute()) {
                echo "Error adding attendance record: " . $stmt_attendance->error . "<br>";
            }
            $stmt_attendance->close();
        }
        
        $stmt_subject->close();
        header("Location: classlist.php");
        exit();
    } else {
        echo "Error adding student: " . $stmt->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="css/addstudent.css">
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
            <li><a href="studentdata.php">
                <i class="uil uil-user"></i>
                <span class="link-name">Student Attendance</span>
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

<div class="container">
    <h1>Add Student</h1>
    <form id="add-student-form" action="addstudent.php" method="POST">
        <label for="student-name">Student Name:</label>
        <input type="text" id="student-name" name="student-name" required>

        <label for="student-section">Section:</label>
        <input type="text" id="student-section" name="student-section" required>

        <label for="guardian-email">Guardian Email:</label>
        <input type="email" id="guardian-email" name="guardian-email" required>

        <label for="guardian-phone">Guardian Contact Number:</label>
        <input type="text" id="guardian-phone" name="guardian-phone" required>

        <label>Subjects:</label>
        <div>
            <input type="checkbox" id="subject-english" name="subjects[]" value="1">
            <label for="subject-english">English</label>
        </div>
        <div>
            <input type="checkbox" id="subject-math" name="subjects[]" value="2">
            <label for="subject-math">Math</label>
        </div>
        <div>
            <input type="checkbox" id="subject-science" name="subjects[]" value="3">
            <label for="subject-science">Science</label>
        </div>
        <div>
            <input type="checkbox" id="subject-filipino" name="subjects[]" value="4">
            <label for="subject-filipino">Filipino</label>
        </div>

        <button type="submit">Add Student</button>
        <a href="classlist.php" class="back-link">Back to Class List</a>
    </form>
</div>
</body>
</html>
