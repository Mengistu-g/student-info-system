<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT 
            t.id, t.name, t.email, 
            d.name AS department 
        FROM teachers t
        LEFT JOIN departments d ON t.department_id = d.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teachers</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Teacher List</h1>
            <a href="add_teacher.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Teacher</a>
        </div>

        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-3">#</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Department</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="p-3"><?php echo $row['id']; ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="p-3"><?php echo $row['department']; ?></td>
                    <td class="p-3">
                        <a href="edit_teacher.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                        <a href="../delete_teacher.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this teacher?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>