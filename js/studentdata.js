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
