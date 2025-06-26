<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Set headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Output column headers
fputcsv($output, ['ID', 'Full Name', 'Email', 'Department', 'Teacher']);

// Fetch student data with joins
$sql = "SELECT 
            s.id, s.fullname, s.email, 
            d.name AS department, 
            t.name AS teacher 
        FROM students s 
        LEFT JOIN departments d ON s.department_id = d.id
        LEFT JOIN teachers t ON s.teacher_id = t.id
        ORDER BY s.id DESC";

$result = $conn->query($sql);

// Write rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['fullname'],
        $row['email'],
        $row['department'],
        $row['teacher']
    ]);
}

fclose($output);
exit;

    //add You went 
// <a href="export_csv.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
//   Export Students CSV
// </a>