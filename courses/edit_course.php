<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$msg = "";

$departments = $conn->query("SELECT * FROM departments");
$teachers = $conn->query("SELECT * FROM teachers");

$course = $conn->query("SELECT * FROM courses WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $description = $_POST['description'];
    $department_id = $_POST['department_id'];
    $teacher_id = $_POST['teacher_id'];

    $stmt = $conn->prepare("UPDATE courses SET name=?, code=?, description=?, department_id=?, teacher_id=? WHERE id=?");
    $stmt->bind_param("sssiii", $name, $code, $description, $department_id, $teacher_id, $id);
    
    if ($stmt->execute()) {
        $msg = "Course updated!";
        header("Location: courses.php");
        exit;
    } else {
        $msg = "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Edit Course</h2>
        <form method="post">
            <input type="text" name="name" value="<?= $course['name'] ?>" class="w-full border p-2 mb-3" required>
            <input type="text" name="code" value="<?= $course['code'] ?>" class="w-full border p-2 mb-3" required>
            <textarea name="description" class="w-full border p-2 mb-3"><?= $course['description'] ?></textarea>
            
            <select name="department_id" class="w-full border p-2 mb-3">
                <?php while ($d = $departments->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>" <?= $d['id'] == $course['department_id'] ? 'selected' : '' ?>>
                        <?= $d['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="teacher_id" class="w-full border p-2 mb-3">
                <?php while ($t = $teachers->fetch_assoc()): ?>
                    <option value="<?= $t['id'] ?>" <?= $t['id'] == $course['teacher_id'] ? 'selected' : '' ?>>
                        <?= $t['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</body>
</html>