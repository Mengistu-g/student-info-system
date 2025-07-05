<?php
session_start();
include "../conn.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";

// Fetch departments and teachers for dropdowns
$departments = $conn->query("SELECT * FROM departments");
$teachers = $conn->query("SELECT * FROM teachers");
$users = $conn->query("SELECT * FROM users");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $description = trim($_POST['description']);
    $department_id = intval($_POST['department_id']);
    $teacher_id = intval($_POST['teacher_id']);
    $user_id = $_SESSION['user_id'];

    // Insert into courses table
    $sql = "INSERT INTO courses (name, code, description, department_id, teacher_id)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $name, $code, $description, $department_id, $teacher_id);

    if ($stmt->execute()) {
        $msg = "<p class='text-green-600'>Course added successfully!</p>";
    } else {
        $msg = "<p class='text-red-600'>Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-center">Add New Course</h2>
        <?php echo $msg; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block mb-1 font-medium">Course Name</label>
                <input type="text" name="name" required class="w-full border px-4 py-2 rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Course Code</label>
                <input type="text" name="code" required class="w-full border px-4 py-2 rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border px-4 py-2 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Department</label>
                <select name="department_id" class="w-full border px-4 py-2 rounded" required>
                    <option value="">-- Select Department --</option>
                    <?php while($dept = $departments->fetch_assoc()): ?>
                        <option value="<?= $dept['id'] ?>"><?= $dept['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium">Teacher</label>
                <select name="teacher_id" class="w-full border px-4 py-2 rounded" required>
                    <option value="">-- Select Teacher --</option>
                    <?php while($teacher = $teachers->fetch_assoc()): ?>
                        <option value="<?= $teacher['id'] ?>"><?= $teacher['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded w-full">
                    Add Course
                </button>
                <p class="mt-4">
            <a href="courses.php" class="text-blue-600 hover:underline">← Back to course List</a>
        </p>
            </div>
        </form>
    </div>
</body>
</html>