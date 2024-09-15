<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Draft</title>
    <link rel="stylesheet" href="css/savedraft.css">
</head>
<body>
    <div class="container">
        <h1>Submit Attendance</h1>
        <div class="students-list">
            <h2>Student List</h2>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Guardian's Email</th>
                        <th>Guardian's Phone Number</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="form-group">
            <button onclick="generateEmails()">Generate Email Drafts</button>
        </div>
    </div>

    <script src="js/savedraft.js"></script>
</body>
</html>