<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";

// Fetch departments and teachers for dropdowns
$departments = $conn->query("SELECT * FROM departments");
$teachers = $conn->query("SELECT * FROM teachers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $description = trim($_POST['description']);
    $department_id = $_POST['department_id'];
    $teacher_id = $_POST['teacher_id'];

    $stmt = $conn->prepare("INSERT INTO courses (name, code, description, department_id, teacher_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $name, $code, $description, $department_id, $teacher_id);

    if ($stmt->execute()) {
        $msg = "Course added successfully.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-gray-100">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Add Course</h2>
        <?php if ($msg): ?>
            <div class="mb-4 text-blue-600 font-semibold"><?= $msg ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="name" placeholder="Course Name" class="w-full border p-2 mb-3" required>
            <input type="text" name="code" placeholder="Course Code" class="w-full border p-2 mb-3" required>
            <textarea name="description" placeholder="Description" class="w-full border p-2 mb-3"></textarea>
            
            <select name="department_id" class="w-full border p-2 mb-3" required>
                <option value="">Select Department</option>
                <?php while ($d = $departments->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <select name="teacher_id" class="w-full border p-2 mb-3" required>
                <option value="">Select Teacher</option>
                <?php while ($t = $teachers->fetch_assoc()): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Course</button>
        </form>
    </div>
</body>
</html>