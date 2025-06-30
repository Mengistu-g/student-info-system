<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: teachers.php");
    exit;
}

// Get teacher info
$stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if (!$teacher) {
    echo "Teacher not found.";
    exit;
}

$departments = $conn->query("SELECT * FROM departments");

// Update form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department_id = $_POST['department_id'];

    // Update teachers table
    $stmt = $conn->prepare("UPDATE teachers SET name=?, email=?, department_id=? WHERE id=?");
    $stmt->bind_param("ssii", $name, $email, $department_id, $id);
    $stmt->execute();

    // Also update users table
    $user_id = $teacher['user_id'];
    $conn->query("UPDATE users SET name='$name', email='$email' WHERE id=$user_id");

    header("Location: teachers.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Teacher</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Edit Teacher</h2>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($teacher['name']); ?>" required class="w-full mb-4 p-3 border rounded" />
            <input type="email" name="email" value="<?php echo htmlspecialchars($teacher['email']); ?>" required class="w-full mb-4 p-3 border rounded" />
            <select name="department_id" required class="w-full mb-4 p-3 border rounded">
                <option value="">Select Department</option>
                <?php while ($dept = $departments->fetch_assoc()): ?>
                    <option value="<?php echo $dept['id']; ?>" <?php if ($dept['id'] == $teacher['department_id']) echo 'selected'; ?>>
                        <?php echo $dept['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        </form>

        <p class="mt-4">
            <a href="teachers.php" class="text-blue-600 hover:underline">← Back to Teacher List</a>
        </p>
    </div>
</body>
</html>