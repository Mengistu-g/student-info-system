<?php
require_once 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("Email already registered.");
    }
    $stmt->close();

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "User registered successfully!";
        // You can redirect to login page or dashboard here
        // header("Location: login.php");
        // exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Registration</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<?= isset($msg) ? $msg : '' ?>

<form method="POST" class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Register User</h2>

    <label class="block mb-2 font-semibold">Name</label>
    <input type="text" name="name" required class="w-full mb-4 p-2 border rounded" />

    <label class="block mb-2 font-semibold">Email</label>
    <input type="email" name="email" required class="w-full mb-4 p-2 border rounded" />

    <label class="block mb-2 font-semibold">Password</label>
    <input type="password" name="password" required class="w-full mb-4 p-2 border rounded" />

    <label class="block mb-2 font-semibold">Role</label>
    <select name="role" required class="w-full mb-6 p-2 border rounded">
        <option value="" disabled selected>Select Role</option>
        <option value="admin">Admin</option>
        <option value="teacher">Teacher</option>
        <option value="student">Student</option>
    </select>

    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Register</button>
</form>

</body>
</html>