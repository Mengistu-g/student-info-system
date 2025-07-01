<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch courses
$sql = "SELECT c.*, d.name AS department_name, t.name AS teacher_name
        FROM courses c
        LEFT JOIN departments d ON c.department_id = d.id
        LEFT JOIN teachers t ON c.teacher_id = t.id
        ORDER BY c.id DESC";
$courses = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Courses Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <div class="flex-1">
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">📖 Courses</h1>
                    <a href="add_course.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ Add Course</a>
                </div>

                <div class="bg-white shadow rounded overflow-x-auto">
                    <table class="min-w-full table-auto text-sm border">
                        <thead class="bg-gray-200 text-left">
                            <tr>
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Code</th>
                                <th class="px-4 py-2 border">Department</th>
                                <th class="px-4 py-2 border">Teacher</th>
                                <th class="px-4 py-2 border">users</th>
                                <th class="px-4 py-2 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($course = $courses->fetch_assoc()) : ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2"><?= htmlspecialchars($course['name']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($course['code']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($course['description']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($course['departemenet_id']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($course['teacher_id']) ?></td>
                                     <td class="border px-4 py-2"><?= htmlspecialchars($course['user_id']) ?></td>
                                    <td class="border px-4 py-2">
                                        <a href="edit_course.php?id=<?= $course['id'] ?>" class="text-blue-600 hover:underline">Edit</a> |
                                        <a href="delete_course.php?id=<?= $course['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure to delete this course?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>