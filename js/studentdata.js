// script.js
document.addEventListener('DOMContentLoaded', function() {
    // Populate dropdowns
    populateDropdowns();

    // Sidebar toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const nav = document.querySelector('nav');

    if (sidebarToggle && nav) {
        sidebarToggle.addEventListener('click', () => {
            nav.classList.toggle('close');
            const isClosed = nav.classList.contains('close');
            localStorage.setItem('status', isClosed ? 'close' : 'open');
        });

        // Initialize sidebar state from localStorage
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

    // Other event listeners
    document.getElementById('add-student').addEventListener('click', () => {
        window.location.href = 'add-student.html';
    });

    document.getElementById('save-draft').addEventListener('click', () => {
        window.location.href = 'save-draft.html';
    });

    document.getElementById('attendance-report').addEventListener('click', () => {
        window.location.href = 'attendance-report.html';
    });

    // Function to populate dropdowns with sample options
    function populateDropdowns() {
        const teachers = ['Teacher A', 'Teacher B', 'Teacher C'];
        const subjects = ['Math', 'Science', 'History'];
        const sections = ['Section 1', 'Section 2', 'Section 3'];

        const teacherSelect = document.getElementById('teacher');
        const subjectSelect = document.getElementById('subject');
        const sectionSelect = document.getElementById('section');

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

        subjectSelect.addEventListener('change', updateTable);
    }

    // Function to format time in 24-hour format
    function formatTime(date) {
        let hours = date.getHours();
        let minutes = date.getMinutes();
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    }

    // Function to update the table based on selected subject
    function updateTable() {
        const subject = document.getElementById('subject').value;
        const tableBody = document.getElementById('attendance-body');

        // Sample data - replace with actual data fetching
        const students = [
            { name: 'Student 1', date: new Date().toLocaleDateString(), time: formatTime(new Date()), status: 'Present' },
            { name: 'Student 2', date: new Date().toLocaleDateString(), time: formatTime(new Date()), status: 'Absent' }
        ];

        tableBody.innerHTML = '';

        students.forEach(student => {
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${student.name}</td>
                <td>${student.date}</td>
                <td>${subject}</td>
                <td>${student.time}</td>
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
        tableBody.querySelectorAll('.update').forEach(button => {
            button.addEventListener('click', function() {
                const studentName = this.closest('tr').querySelector('td').textContent;
                window.location.href = `update-student.html?name=${encodeURIComponent(studentName)}`;
            });
        });

        tableBody.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this student?')) {
                    this.closest('tr').remove();
                }
            });
        });
    }

    // Initialize the table
    updateTable();
});
