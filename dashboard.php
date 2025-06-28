<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Counts
$students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc();
$teachers = $conn->query("SELECT COUNT(*) AS total FROM teachers")->fetch_assoc();
$departments = $conn->query("SELECT COUNT(*) AS total FROM departments")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
    
 <aside class="w-64 bg-white shadow-lg min-h-screen hidden md:block">
  <div class="p-4 text-xl font-bold border-b border-gray-200">SIS Panel</div>
  <nav class="p-4 space-y-3 text-gray-700">
    <a href="dashboard.php" class="block hover:bg-gray-100 rounded px-3 py-2 transition">🏠 Dashboard</a>
    <a href="students/students.php" class="block hover:bg-gray-100 rounded px-3 py-2 transition">📚 Students</a>
    <a href="teachers/teachers.php" class="block hover:bg-gray-100 rounded px-3 py-2 transition">👩‍🏫 Teachers</a>
    <a href="departments/departments.php" class="block hover:bg-gray-100 rounded px-3 py-2 transition">🏛️ Departments</a>
    <a href="logout.php" class="block text-red-600 hover:bg-red-100 rounded px-3 py-2 transition">🚪 Logout</a>
  </nav>
</aside>

    <div class="flex-1">
        <?php include "includes/header.php"; ?>

        <main class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow text-center">
                    <h2 class="text-xl font-semibold">📚 Students</h2>
                    <p class="text-4xl text-blue-600 mt-2"><?php echo $students['total']; ?></p>
                </div>
                <div class="bg-white p-6 rounded shadow text-center">
                    <h2 class="text-xl font-semibold">👩‍🏫 Teachers</h2>
                    <p class="text-4xl text-green-600 mt-2"><?php echo $teachers['total']; ?></p>
                </div>
                <div class="bg-white p-6 rounded shadow text-center">
                    <h2 class="text-xl font-semibold">🏛️ Departments</h2>
                    <p class="text-4xl text-purple-600 mt-2"><?php echo $departments['total']; ?></p>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>