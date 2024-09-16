<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <link rel="stylesheet" href="css/studentdata.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <nav>
        <!-- Sidebar code from the home page -->
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
                <li><a href="#">
                    <i class="uil uil-schedule"></i>
                    <span class="link-name">Schedule</span>
                </a></li>
                <li><a href="savedraft.php">
                    <i class="uil uil-check-circle"></i>
                    <span class="link-name">Attendance Submit</span>
                </a></li>
                <li><a href="#">
                    <i class="uil uil-share"></i>
                    <span class="link-name">Share</span>
                </a></li>
            </ul>

            <ul class="logout-mode">
                <li><a href="#">
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
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
        </div>

    <div class="dashboard">
        <div class="container">
            <div class="controls">
                <label for="teacher">Teacher:</label>
                <select id="teacher">
                    <!-- Options will be populated dynamically -->
                </select>

                <label for="subject">Subject:</label>
                <select id="subject">
                    <!-- Options will be populated dynamically -->
                </select>

                <label for="section">Section:</label>
                <select id="section">
                    <!-- Options will be populated dynamically -->
                </select>

                <button onclick="window.location.href='addstudent.php';">Add Student</button>
            </div>

            <h1>Student Attendance</h1>

            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Time</th>
                        <th>Attendance Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="attendance-body">
                    <!-- Rows will be populated dynamically -->
                    <tr>
                        <td>John Doe</td>
                        <td>2023-09-15</td>
                        <td>Math</td>
                        <td>10:00 AM</td>
                        <td>
                            <select>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                        <td>
                        <button onclick="window.location.href='updatestudent.php?id=1';">Update</button>
                        <button onclick="deleteStudent(1);">Delete</button>

                        </td>
                    </tr>
                    <!-- Additional rows can be added here dynamically -->
                </tbody>
            </table>

            <div class="actions">
                <button onclick="window.location.href='savedraft.php';">Save Draft</button>
                <button onclick="window.location.href='attendancereport.php';">Attendance Report</button>
            </div>
        </div>
    </div>
    
    <script src="js/studentdata.js"></script>
    
    <script>
        function deleteStudent(studentId) {
            // Here you can add confirmation prompt if needed
            if (confirm('Are you sure you want to delete this student?')) {
                // This will be replaced with actual server-side delete logic
                window.location.href = 'deletestudent.php?id=' + studentId;
            }
        }
    </script>
</body>
</html>
