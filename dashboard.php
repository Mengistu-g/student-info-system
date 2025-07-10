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
$courses = $conn->query("SELECT COUNT(*) AS total FROM courses")->fetch_assoc(); // ✅ Add this line
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard To SIS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cover bg-center min-h-screen" style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f');">

<div class="flex">

    <?php include "includes/sidbar.php"; ?>

    <div class="flex-1">
        <?php include "includes/header.php"; ?>

        <main class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                <div class="bg-white p-6 rounded shadow text-center">
                    <h2 class="text-xl font-semibold">📖 Courses</h2>
                    <p class="text-4xl text-red-600 mt-2"><?php echo $courses['total']; ?></p>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>