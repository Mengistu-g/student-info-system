<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: students.php");
    exit;
}

// Get student info
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit;
}

// Get departments and teachers
$departments = $conn->query("SELECT * FROM departments");
$teachers = $conn->query("SELECT * FROM teachers");

// Update on form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $department_id = $_POST['department_id'];
    $teacher_id = $_POST['teacher_id'];

    // Update students table
    $stmt = $conn->prepare("UPDATE students SET fullname=?, email=?, department_id=?, teacher_id=? WHERE id=?");
    $stmt->bind_param("ssiii", $fullname, $email, $department_id, $teacher_id, $id);
    $stmt->execute();

    // Also update users table
    $user_id = $student['user_id'];
    $conn->query("UPDATE users SET name='$fullname', email='$email' WHERE id=$user_id");

    header("Location: students.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Edit Student</h2>
        <form method="POST">
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($student['fullname']); ?>" required class="w-full mb-4 p-3 border rounded" />
            <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required class="w-full mb-4 p-3 border rounded" />

            <select name="department_id" required class="w-full mb-4 p-3 border rounded">
                <option value="">Select Department</option>
                <?php while($dept = $departments->fetch_assoc()): ?>
                    <option value="<?php echo $dept['id']; ?>" <?php if ($dept['id'] == $student['department_id']) echo 'selected'; ?>>
                        <?php echo $dept['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="teacher_id" required class="w-full mb-4 p-3 border rounded">
                <option value="">Select Teacher</option>
                <?php while($teacher = $teachers->fetch_assoc()): ?>
                    <option value="<?php echo $teacher['id']; ?>" <?php if ($teacher['id'] == $student['teacher_id']) echo 'selected'; ?>>
                        <?php echo $teacher['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        </form>

        <p class="mt-4">
            <a href="students.php" class="text-blue-600 hover:underline">← Back to Student List</a>
        </p>
    </div>
</body>
</html>