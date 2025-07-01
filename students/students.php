<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT 
            s.id, s.fullname, s.email, 
            d.name AS department, 
            t.name AS teacher 
        FROM students s 
        LEFT JOIN departments d ON s.department_id = d.id
        LEFT JOIN teachers t ON s.teacher_id = t.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Student List</h1>
             <a href="../exports/export_csv.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export CSV</a>
            <a href="../exports/export_pdf.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Export PDF</a>
            <a href="add_student.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Student</a>
        </div>

        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-3">id</th>
                    <th class="p-3">Full Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Department</th>
                    <th class="p-3">Teacher</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="p-3"><?php echo $row['id']; ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="p-3"><?php echo $row['department']; ?></td>
                    <td class="p-3"><?php echo $row['teacher']; ?></td>
                    <td class="p-3">
                        <a href="edit_students.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                        <a href="../delete_student.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>