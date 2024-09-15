function generateEmails() {
    const rows = document.querySelectorAll('.students-table tbody tr');
    rows.forEach(row => {
        const studentName = row.cells[0].textContent;
        const guardianEmail = row.cells[1].textContent;
        const guardianPhone = row.cells[2].textContent;

        // Static message for simplicity
        const emailBody = `Dear Guardian,\n\n${studentName} was present today.\n\nBest regards,\nYour School`;
        const mailtoLink = `mailto:${guardianEmail}?subject=Attendance Notification&body=${encodeURIComponent(emailBody)}`;

        // Open mailto link for each student's guardian
        window.open(mailtoLink, '_blank');
    });
}