<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if ($name && $email) {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $email, $hashed, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $email, $user_id);
        }

        $stmt->execute();
        $msg = "Profile updated successfully!";
        $user['name'] = $name;
        $user['email'] = $email;
    } else {
        $msg = "Name and email are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
    <div class="flex-1">
        <main class="p-6 max-w-xl mx-auto">
            <h2 class="text-2xl font-bold mb-4">My Profile</h2>

            <?php if ($msg): ?>
                <div class="mb-4 text-green-600 font-semibold">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="bg-white p-6 rounded shadow space-y-4">
                <div>
                    <label class="block font-semibold mb-1">Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="block font-semibold mb-1">New Password <span class="text-sm text-gray-500">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" class="w-full border p-2 rounded">
                </div>

                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Profile</button>
                </div>
            </form>
        </main>
    </div>
</div>

</body>
</html>