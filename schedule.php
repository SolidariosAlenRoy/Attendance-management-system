<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .schedule-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 600px;  
            height: 600px; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden; 
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            height: 100%;
            table-layout: fixed; 
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            text-align: center;
            padding: 5px; 
            font-size: 12px; 
        }

        
        tbody tr {
            height: calc(100% / 30); 
        }

        .time-slot {
            background-color: #f2f2f2;
            font-weight: bold;
            white-space: nowrap; 
        }

        .class-cell {
            background-color: #a8d08d;
        }
        
        @media (max-width: 768px) {
            .schedule-container {
                width: 90%;
                height: 90%;
            }

            th, td {
                padding: 3px; 
                font-size: 10px;
            }
        }
    </style>
</head>
<body>

<div class="schedule-container">
    <h1>CLASS SCHEDULE</h1>

    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
            </tr>
        </thead>
        <tbody>
            <!-- Sample Time Slots -->
            <tr>
                <td class="time-slot">7:00 AM</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
                <td class="time-slot">9:30 AM</td>
                <td></td>
                <td class="class-cell">NET 402 - BSIT 3-A - INET</td>
                <td></td>
                <td class="class-cell">NET 402 - BSIT 3-A - INET</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="time-slot">10:00 AM</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="time-slot">2:30 PM</td>
                <td class="class-cell">SS 601 - BSIT 3-A - COMP LAB B</td>
                <td class="class-cell">ITE 603 - BSIT 3-A - COMP LAB C</td>
                <td class="class-cell">SS 601 - BSIT 3-A - COMP LAB B</td>
                <td class="class-cell">ITE 603 - BSIT 3-A - COMP LAB C</td>
                <td class="class-cell">SS 601 - BSIT 3-A - COMP LAB B</td>
                <td></td>
            </tr>
            <tr>
                <td class="time-slot">3:30 PM</td>
                <td class="class-cell">HS 601 - BSIT 3-A - COMP LAB B</td>
                <td></td>
                <td class="class-cell">HS 601 - BSIT 3-A - COMP LAB B</td>
                <td></td>
                <td class="class-cell">HS 601 - BSIT 3-A - COMP LAB B</td>
                <td></td>
            </tr>
            <tr>
                <td class="time-slot">6:30 PM</td>
                <td class="class-cell">IM 401 - BSIT 3-A - COMP LAB B</td>
                <td></td>
                <td class="class-cell">IM 401 - BSIT 3-A - COMP LAB B</td>
                <td class="class-cell">SIA 401 - BSIT 3-A - COMP LAB A</td>
                <td class="class-cell">IM 401 - BSIT 3-A - COMP LAB B</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
