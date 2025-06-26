<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";

$departments = $conn->query("SELECT * FROM departments");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department_id = $_POST['department_id'];

    // Insert user with role teacher
    $password = password_hash("teacher123", PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'teacher')");
    $user_id = $conn->insert_id;

    // Insert teacher
    $stmt = $conn->prepare("INSERT INTO teachers (name, email, department_id, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $name, $email, $department_id, $user_id);
    $stmt->execute();

    $msg = "Teacher added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Teacher</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Add Teacher</h2>

        <?php if ($msg): ?>
            <p class="text-green-600 mb-4"><?php echo $msg; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required class="w-full mb-4 p-3 border rounded" />
            <input type="email" name="email" placeholder="Email" required class="w-full mb-4 p-3 border rounded" />

            <select name="department_id" required class="w-full mb-4 p-3 border rounded">
                <option value="">Select Department</option>
                <?php while($dept = $departments->fetch_assoc()): ?>
                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Teacher</button>
        </form>

        <p class="mt-4">
            <a href="teachers.php" class="text-blue-600 hover:underline">← Back to Teacher List</a>
        </p>
    </div>
</body>
</html>