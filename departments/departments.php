<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$result = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Departments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Departments</h1>
       <a href="../exports/export_csv.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export CSV</a>
        <a href="../exports/export_pdf.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Export PDF</a>
        <a href="add_department.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Department</a>

    </div>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-3">id</th>
                <th class="p-3">Department Name</th>
                <th class="p-3">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr class="border-b">
                <td class="p-3"><?php echo $row['id']; ?></td>
                <td class="p-3"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="p-3">
                    <a href="edit_departement.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                    <a href="../delete_department.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this department?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>