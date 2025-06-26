<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch logged-in user's name
$user = $conn->query("SELECT name FROM users WHERE id = $user_id")->fetch_assoc();

// Count students
$students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc();
// Count teachers
$teachers = $conn->query("SELECT COUNT(*) AS total FROM teachers")->fetch_assoc();
// Count departments
$departments = $conn->query("SELECT COUNT(*) AS total FROM departments")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="text-lg font-bold">🎓 Student Information System</div>
        <div class="space-x-4">
            <span class="text-gray-700">Welcome, <strong><?php echo htmlspecialchars($user['name']); ?></strong></span>
            <a href="profile.php" class="text-blue-600 hover:underline">Profile</a>
            <a href="logout.php" class="text-red-600 hover:underline">Logout</a>
        </div>
    </nav>

    <main class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="text-xl font-bold text-gray-700">📚 Students</h2>
                <p class="text-4xl mt-2 text-blue-600"><?php echo $students['total']; ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="text-xl font-bold text-gray-700">👩‍🏫 Teachers</h2>
                <p class="text-4xl mt-2 text-green-600"><?php echo $teachers['total']; ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="text-xl font-bold text-gray-700">🏛️ Departments</h2>
                <p class="text-4xl mt-2 text-purple-600"><?php echo $departments['total']; ?></p>
            </div>
        </div>
    </main>
</body>
</html>