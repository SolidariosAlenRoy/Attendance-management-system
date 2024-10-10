<?php
require_once("dbconnection.php");

$selected_section = isset($_POST['section']) ? $_POST['section'] : '';

// Fetch all sections for the dropdown
$section_query = "SELECT DISTINCT section FROM students";
$section_result = mysqli_query($conn, $section_query);

// Fetch students based on selected section
$query = "SELECT * FROM students";
if ($selected_section != '') {
    $query .= " WHERE section = ?";
}
$query .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $query);
if ($selected_section != '') {
    mysqli_stmt_bind_param($stmt, "s", $selected_section);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class List</title>
    <style>
        /* General styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

/* Form styling */
form {
    margin-bottom: 20px;
    text-align: center;
}

label {
    font-weight: bold;
    margin-right: 10px;
}

select {
    padding: 5px 10px;
    font-size: 16px;
}

button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    margin: 10px;
}

button:hover {
    background-color: #0056b3;
}

/* Table styling */
.container {
    max-width: 1100px;
    margin-left: auto; /* This pushes the container to the right */
    margin-right: 80px; /* Optional: Adds a margin to the right side */
    padding: 20px;
}


table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background-color: #007bff;
    color: white;
}

.back-link {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
}

.back-link:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 768px) {
    table, th, td {
        font-size: 14px;
    }

    button, .back-link {
        font-size: 14px;
        padding: 8px 15px;
    }

    .container {
        padding: 10px;
    }
}
/* ===== Google Font Import - Poppins ===== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

:root{
    /* ===== Colors ===== */
    --primary-color: #0E4BF1;
    --panel-color: #FFF;
    --text-color: #000;
    --black-light-color: #707070;
    --border-color: #e6e5e5;
    --toggle-color: #DDD;
    --box1-color: #4DA3FF;
    --box2-color: #FFE6AC;
    --box3-color: #E7D1FC;
    --title-icon-color: #fff;
    
    /* ====== Transition ====== */
    --tran-05: all 0.5s ease;
    --tran-03: all 0.3s ease;
    --tran-03: all 0.2s ease;
}
/* === Custom Scroll Bar CSS === */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 12px;
    transition: all 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background: #0b3cc1;
}

body.dark::-webkit-scrollbar-thumb:hover,
body.dark .activity-data::-webkit-scrollbar-thumb:hover{
    background: #3A3B3C;
}

nav{
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 250px;
    padding: 10px 14px;
    background-color: var(--panel-color);
    border-right: 1px solid var(--border-color);
    transition: var(--tran-05);
}
nav.close{
    width: 73px;
}
nav .logo-name{
    display: flex;
    align-items: center;
}
nav .logo-image{
    display: flex;
    justify-content: center;
    min-width: 45px;
}
nav .logo-image img{
    width: 40px;
    object-fit: cover;
    border-radius: 50%;
}

nav .logo-name .logo_name{
    font-size: 22px;
    font-weight: 600;
    color: var(--text-color);
    margin-left: 14px;
    transition: var(--tran-05);
}
nav.close .logo_name{
    opacity: 0;
    pointer-events: none;
}
nav .menu-items{
    margin-top: 40px;
    height: calc(100% - 90px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.menu-items li{
    list-style: none;
}
.menu-items li a{
    display: flex;
    align-items: center;
    height: 50px;
    text-decoration: none;
    position: relative;
}
.nav-links li a:hover:before{
    content: "";
    position: absolute;
    left: -7px;
    height: 5px;
    width: 5px;
    border-radius: 50%;
    background-color: var(--primary-color);
}
body.dark li a:hover:before{
    background-color: var(--text-color);
}
.menu-items li a i{
    font-size: 24px;
    min-width: 45px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--black-light-color);
}
.menu-items li a .link-name{
    font-size: 18px;
    font-weight: 400;
    color: var(--black-light-color);    
    transition: var(--tran-05);
}
nav.close li a .link-name{
    opacity: 0;
    pointer-events: none;
}
.nav-links li a:hover i,
.nav-links li a:hover .link-name{
    color: var(--primary-color);
}
body.dark .nav-links li a:hover i,
body.dark .nav-links li a:hover .link-name{
    color: var(--text-color);
}
.menu-items .logout-mode{
    padding-top: 10px;
    border-top: 1px solid var(--border-color);
}
.menu-items .mode{
    display: flex;
    align-items: center;
    white-space: nowrap;
}
.menu-items .mode-toggle{
    position: absolute;
    right: 14px;
    height: 50px;
    min-width: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.mode-toggle .switch{
    position: relative;
    display: inline-block;
    height: 22px;
    width: 40px;
    border-radius: 25px;
    background-color: var(--toggle-color);
}
.switch:before{
    content: "";
    position: absolute;
    left: 5px;
    top: 50%;
    transform: translateY(-50%);
    height: 15px;
    width: 15px;
    background-color: var(--panel-color);
    border-radius: 50%;
    transition: var(--tran-03);
}
body.dark .switch:before{
    left: 20px;
}

    </style>
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
        <h1>Class List</h1>
        <form method="POST" action="classlist.php">
            <label for="section">Select Section:</label>
            <select name="section" id="section" onchange="this.form.submit()">
                <option value="">All Sections</option>
                <?php while ($row = mysqli_fetch_assoc($section_result)) {
                    echo "<option value='".$row['section']."' ".($selected_section == $row['section'] ? "selected" : "").">".$row['section']."</option>";
                } ?>
            </select>
        </form>
        <button onclick="window.location.href='addstudent.php';">Add Student</button>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Section</th>
                    <th>Guardian Email</th>
                    <th>Guardian Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) {
                    while ($res = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$res['student_name']."</td>";
                        echo "<td>".$res['section']."</td>";
                        echo "<td>".$res['guardian_email']."</td>";
                        echo "<td>".$res['guardian_phone']."</td>";
                        echo "<td>
                                <a href=\"updatestudent.php?id=".$res['id']."\">Update</a> | 
                                <a href=\"delete.php?id=".$res['id']."\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No students found for the selected section.</td></tr>";
                } ?>
            </tbody>
        </table>
        <a href="studentdata.php" class="back-link">Back to Student Attendance</a>
    </div>
</body>
</html>
