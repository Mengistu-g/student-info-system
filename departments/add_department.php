<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    if ($name) {
        // Insert new department
        $stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $msg = "Department added successfully!";
    } else {
        $msg = "Please enter a department name.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Department</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Add Department</h2>

    <?php if ($msg): ?>
        <p class="<?php echo ($msg === 'Department added successfully!') ? 'text-green-600' : 'text-red-600'; ?> mb-4"><?php echo $msg; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Department Name" required class="w-full mb-4 p-3 border rounded" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Department</button>
    </form>

    <p class="mt-4">
        <a href="departments.php" class="text-blue-600 hover:underline">← Back to Departments</a>
    </p>
</div>
</body>
</html>