<?php
$user = $conn->query("SELECT name FROM users WHERE id = {$_SESSION['user_id']}")->fetch_assoc();
?>
<div class="w-full bg-white shadow p-4 flex justify-between items-center">
    <div class="text-xl font-bold">🎓 Student Information System</div>
    <div class="text-sm text-gray-700">
        👋 Welcome : <strong><?php echo htmlspecialchars($user['name']); ?></strong>
    |<a href="profile.php" class="text-blue-600 hover:underline">Profile</a>
         <a href="logout.php" class="block text-red-600 hover:bg-red-100 rounded px-3 py-2 transition">🚪 Logout</a>
    </div>
</div>
