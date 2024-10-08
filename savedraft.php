<?php
require_once("dbconnection.php");

// Fetch absent students
$sql = "SELECT s.student_name, s.guardian_email, s.guardian_phone
        FROM students s
        JOIN attendance a ON s.id = a.student_id
        WHERE a.attendance_status = 'Absent'";

$result = $conn->query($sql);

// Check if there are absent students
$rows = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    $rows = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Notification</title>
    <link rel="stylesheet" href="css/savedraft.css">
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
        <h1>Absent Students Notification</h1>
        <div class="students-list">
            <h2>Absent Students List</h2>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Guardian's Email</th>
                        <th>Guardian's Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['guardian_email']); ?></td>
                                <td><?php echo htmlspecialchars($row['guardian_phone']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No absent students.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <button onclick="generateEmails()">Generate Email Drafts</button>
        </div>
        <a href="studentdata.php" class="back-link">Back to Student Attendance</a>
    </div>
    
    <script>
        function generateEmails() {
    const rows = document.querySelectorAll('.students-table tbody tr');
    const emailAddresses = [];
    let emailBody = "Dear Guardians,\n\nWe would like to inform you that the following students were absent today:\n\n";
    
    if (rows.length === 1 && rows[0].cells[0].colSpan === 3) {
        alert("No absent students to notify.");
        return;
    }
    
    rows.forEach(row => {
        const studentName = row.cells[0].textContent.trim();
        const guardianEmail = row.cells[1].textContent.trim();

        if (guardianEmail && !emailAddresses.includes(guardianEmail)) {
            emailAddresses.push(guardianEmail);
        }
        
        emailBody += `- ${studentName}\n`;
    });

    emailBody += "\nBest regards,\nYour School";
    const subject = "Attendance Notification";
    
    if (emailAddresses.length > 0) {
        const mailtoLink = `mailto:${emailAddresses.join(',')}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(emailBody)}`;
        
        // Open mailto link in a new tab
        window.open(mailtoLink, '_blank');
    } else {
        alert("No valid guardian emails found.");
    }
}


    </script>
</body>
</html>
