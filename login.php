<?php
session_start();
include "conn.php"; // Make sure conn.php connects to MySQL using mysqli

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
// echo $user['password'];
// echo '<br />';
// echo $hashedPassword;
// echo '<br />';
// echo $hashed_password;

        if ($password === $user['password']) {
        // if (password_verify($password, $hashedPassword)) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            if ($user['role'] === 'admin') {
                header("Location: dashboard.php");
            } elseif ($user['role'] === 'teacher') {
                header("Location: teachers/teachers.php");
            } else {
                header("Location: students/students.php");
            }
            exit;
        } else {
            $msg = "Invalid password.";
        }
    } else {
        $msg = "User not found.";
    }
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-100 via-white to-green-100">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold text-center mb-6">Login</h2>
        <?php if ($msg): ?>
            <p class="text-red-600 mb-4 text-center"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required class="w-full mb-4 p-3 border border-gray-300 rounded" />
            <input type="password" name="password" placeholder="Password" required class="w-full mb-4 p-3 border border-gray-300 rounded" />
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        </form>
    </div>
</body>
</html>