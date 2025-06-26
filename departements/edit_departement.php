<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: departments.php");
    exit;
}

// Get department info
$stmt = $conn->prepare("SELECT * FROM departments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$department = $result->fetch_assoc();

if (!$department) {
    echo "Department not found.";
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    if ($name) {
        $stmt = $conn->prepare("UPDATE departments SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $msg = "Department updated successfully!";
        // Reload updated data
        $department['name'] = $name;
    } else {
        $msg = "Please enter a department name.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Department</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Department</h2>

    <?php if ($msg): ?>
        <p class="<?php echo ($msg === 'Department updated successfully!') ? 'text-green-600' : 'text-red-600'; ?> mb-4"><?php echo $msg; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" value="<?php echo htmlspecialchars($department['name']); ?>" required class="w-full mb-4 p-3 border rounded" />
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update Department</button>
    </form>

    <p class="mt-4">
        <a href="departments.php" class="text-blue-600 hover:underline">← Back to Departments</a>
    </p>
</div>
</body>
</html>