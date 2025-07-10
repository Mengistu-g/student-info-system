<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// === Pagination Setup ===
$limit = 5; // Number of courses per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// === Get total number of courses ===
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM courses");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// === Fetch paginated courses ===
$sql = "SELECT c.*, d.name AS department_name, t.name AS teacher_name
        FROM courses c
        LEFT JOIN departments d ON c.department_id = d.id
        LEFT JOIN teachers t ON c.teacher_id = t.id
        ORDER BY c.id DESC
        LIMIT $limit OFFSET $offset";
$courses = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Courses Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">COURSE List</h1>
                <a href="add_course.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">+ Add Course</a>
            </div>

            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full table-auto text-sm border">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Code</th>
                            <th class="px-4 py-2 border">Description</th>
                            <th class="px-4 py-2 border">Department</th>
                            <th class="px-4 py-2 border">Teacher</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($course = $courses->fetch_assoc()) : ?>
                        <tr class="border-b">
                            <td class="p-3"><?php echo htmlspecialchars($course['name']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($course['code']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($course['description']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($course['department_name']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($course['teacher_name']); ?></td>
                            <td class="p-3">
                                <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                                <a href="../delete_course.php?id=<?php echo $course['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="mt-6 flex justify-center space-x-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="px-3 py-1 <?php echo ($i == $page) ? 'bg-blue-600 text-white' : 'bg-gray-200'; ?> rounded hover:bg-blue-500">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Next</a>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>