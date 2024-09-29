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
    <div class="container">
        <h1>Absent Students Notification</h1>
        <div class="students-list">
            <h2>Absent Students List</h2>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Student Name</th>
                        <th>Guardian's Email</th>
                        <th>Guardian's Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><input type="checkbox" class="student-checkbox" data-name="<?php echo htmlspecialchars($row['student_name']); ?>" data-email="<?php echo htmlspecialchars($row['guardian_email']); ?>"></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['guardian_email']); ?></td>
                                <td><?php echo htmlspecialchars($row['guardian_phone']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No absent students.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <button onclick="generateEmails()">Generate Email Draft</button>
            <button onclick="generateAllEmails()">Generate All Drafts</button> <!-- New Button -->
        </div>
        <a href="studentdata.php" class="back-link">Back to Student Attendance</a>
    </div>
    
    <script>
        function generateEmails() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked'); // Select checked checkboxes
            if (checkboxes.length === 0) {
                alert('Please select at least one student.');
                return;
            }

            checkboxes.forEach(checkbox => {
                const studentName = checkbox.getAttribute('data-name'); // Get student name from data attribute
                const guardianEmail = checkbox.getAttribute('data-email'); // Get guardian email from data attribute

                // Email body informing about absence
                const emailBody = `Dear Guardian,\n\nWe would like to inform you that ${studentName} was absent today.\n\nBest regards,\nYour School`;
                const mailtoLink = `mailto:${guardianEmail}?subject=Attendance Notification&body=${encodeURIComponent(emailBody)}`;
                
                // Open mailto link for each selected student's guardian
                window.open(mailtoLink, '_blank');
            });
        }

        function generateAllEmails() {
            const checkboxes = document.querySelectorAll('.student-checkbox'); // Select all checkboxes
            if (checkboxes.length === 0) {
                alert('No absent students to generate drafts for.');
                return;
            }

            checkboxes.forEach(checkbox => {
                const studentName = checkbox.getAttribute('data-name'); // Get student name from data attribute
                const guardianEmail = checkbox.getAttribute('data-email'); // Get guardian email from data attribute

                // Email body informing about absence
                const emailBody = `Dear Guardian,\n\nWe would like to inform you that ${studentName} was absent today.\n\nBest regards,\nYour School`;
                const mailtoLink = `mailto:${guardianEmail}?subject=Attendance Notification&body=${encodeURIComponent(emailBody)}`;
                
                // Open mailto link for each student's guardian
                window.open(mailtoLink, '_blank');
            });
        }
    </script>
</body>
</html>

