<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$students = $conn->query("SELECT * FROM students");
$courses = $conn->query("SELECT * FROM courses");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];

    $stmt = $conn->prepare("INSERT IGNORE INTO course_enrollments (student_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $course_id);
    if ($stmt->execute()) {
        $msg = "Student enrolled successfully.";
    } else {
        $msg = "Error enrolling student.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enroll Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-gray-100">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Enroll Student in Course</h2>
        <?php if ($msg): ?>
            <p class="mb-4 text-green-700"><?= $msg ?></p>
        <?php endif; ?>
        <form method="post">
            <select name="student_id" class="w-full border p-2 mb-3" required>
                <option value="">Select Student</option>
                <?php while ($s = $students->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= $s['fullname'] ?></option>
                <?php endwhile; ?>
            </select>

            <select name="course_id" class="w-full border p-2 mb-3" required>
                <option value="">Select Course</option>
                <?php while ($c = $courses->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['name'] ?> (<?= $c['code'] ?>)</option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enroll</button>
        </form>
    </div>
</body>
</html>