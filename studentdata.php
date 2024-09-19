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
        <div class="logo-name">
            <div class="logo-image">
               <img src="image/classtrack.png" alt="Logo">
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
                <li><a href="schedule.php">
                    <i class="uil uil-schedule"></i>
                    <span class="link-name">Schedule</span>
                </a></li>
                <li><a href="savedraft.php">
                    <i class="uil uil-check-circle"></i>
                    <span class="link-name">Attendance Submit</span>
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
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
        </div>

        <div class="container">
            <div class="controls">
                <label for="teacher">Teacher:</label>
                <select id="teacher"></select>

                <label for="subject">Subject:</label>
                <select id="subject"></select>

                <label for="section">Section:</label>
                <select id="section"></select>

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
                    <!-- Rows will be dynamically populated -->
                </tbody>
            </table>

            <div class="actions">
                <button onclick="window.location.href='savedraft.php';">Save Draft</button>
                <button onclick="window.location.href='attendancereport.php';">Attendance Report</button>
            </div>
        </div>
    </section>
    
    <script>
        
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar toggle
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const nav = document.querySelector('nav');

        if (sidebarToggle && nav) {
            sidebarToggle.addEventListener('click', () => {
                nav.classList.toggle('close');
                const isClosed = nav.classList.contains('close');
                localStorage.setItem('status', isClosed ? 'close' : 'open');
            });

            const savedStatus = localStorage.getItem('status');
            if (savedStatus === 'close') {
                nav.classList.add('close');
            }
        }

        // Dark mode toggle
        const body = document.querySelector('body');
        const modeToggle = document.querySelector('.mode-toggle');

        if (modeToggle) {
            let savedMode = localStorage.getItem('mode');
            if (savedMode === 'dark') {
                body.classList.add('dark');
            }

            modeToggle.addEventListener('click', () => {
                body.classList.toggle('dark');
                const isDark = body.classList.contains('dark');
                localStorage.setItem('mode', isDark ? 'dark' : 'light');
            });
        }

        // Function to populate dropdowns
        function populateDropdowns() {
            const teachers = [' ', 'Teacher A', 'Teacher B', 'Teacher C'];
            const subjects = [' ', 'Math', 'Science', 'History'];
            const sections = [' ', 'Section 1', 'Section 2', 'Section 3'];

            const teacherSelect = document.getElementById('teacher');
            const subjectSelect = document.getElementById('subject');
            const sectionSelect = document.getElementById('section');

            // Clear existing options to avoid duplication
            teacherSelect.innerHTML = '';
            subjectSelect.innerHTML = '';
            sectionSelect.innerHTML = '';

            teachers.forEach(teacher => {
                let option = document.createElement('option');
                option.textContent = teacher;
                teacherSelect.appendChild(option);
            });

            subjects.forEach(subject => {
                let option = document.createElement('option');
                option.textContent = subject;
                subjectSelect.appendChild(option);
            });

            sections.forEach(section => {
                let option = document.createElement('option');
                option.textContent = section;
                sectionSelect.appendChild(option);
            });

            // Update table when the subject changes
            subjectSelect.addEventListener('change', updateTable);
        }

        // Function to format the time
        function formatTime(date) {
            let hours = date.getHours();
            let minutes = date.getMinutes();
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        }

        // Function to simulate student data based on the selected subject
        function getStudentsForSubject(subject) {
            const studentsData = {
                'Math': [
                    { name: 'Student A', date: new Date().toLocaleDateString(), status: '' },
                    { name: 'Student B', date: new Date().toLocaleDateString(), status: '' }
                ],
                'Science': [
                    { name: 'Student C', date: new Date().toLocaleDateString(), status: '' },
                    { name: 'Student D', date: new Date().toLocaleDateString(), status: '' }
                ],
                'History': [
                    { name: 'Student E', date: new Date().toLocaleDateString(), status: '' },
                    { name: 'Student F', date: new Date().toLocaleDateString(), status: '' }
                ]
            };
            return studentsData[subject] || [];
        }

        // Function to update the table
        function updateTable() {
            const subject = document.getElementById('subject').value;
            const tableBody = document.getElementById('attendance-body');
            const students = getStudentsForSubject(subject);

            tableBody.innerHTML = ''; // Clear the table before updating

            students.forEach(student => {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.name}</td>
                    <td>${student.date}</td>
                    <td>${subject}</td>
                    <td>${formatTime(new Date())}</td>
                    <td>
                        <select class="attendance-status">
                            <option ${student.status === 'Present' ? 'selected' : ''}>Present</option>
                            <option ${student.status === 'Absent' ? 'selected' : ''}>Absent</option>
                            <option ${student.status === 'Late' ? 'selected' : ''}>Late</option>
                        </select>
                    </td>
                    <td>
                        <button class="update">Update</button>
                        <button class="delete">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Add event listeners for Update and Delete buttons
            addRowEventListeners();
        }

        // Function to handle row buttons (Update and Delete)
        function addRowEventListeners() {
            const tableBody = document.getElementById('attendance-body');
            
            tableBody.querySelectorAll('.update').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const studentName = row.querySelector('td:first-child').textContent;
                    const status = row.querySelector('.attendance-status').value;
                    alert(`Updating ${studentName} status to ${status}`);
                    // Here, you could also redirect to an update page with query params if needed
                });
            });

            tableBody.querySelectorAll('.delete').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const studentName = row.querySelector('td:first-child').textContent;
                    if (confirm(`Are you sure you want to delete ${studentName}?`)) {
                        row.remove(); // Remove student row from table
                    }
                });
            });
        }

        // Initialize the page
        populateDropdowns();
        updateTable(); // Call this initially to populate the table
    });


    </script>
</body>
</html>
