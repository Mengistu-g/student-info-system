<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// === Pagination Setup ===
$limit = 5; // Number of teachers per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$where = "";

if (!empty($search)) {
    $safeSearch = $conn->real_escape_string($search);
    $where = "WHERE t.name LIKE '%$safeSearch%' OR t.email LIKE '%$safeSearch%'";
}

// Count total records (with search if present)
$countSql = "SELECT COUNT(*) AS total 
             FROM teachers t 
             LEFT JOIN departments d ON t.department_id = d.id 
             $where";
$totalResult = $conn->query($countSql);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Get filtered + paginated result
$sql = "SELECT 
            t.id, t.name, t.email, 
            d.name AS department 
        FROM teachers t
        LEFT JOIN departments d ON t.department_id = d.id
        $where
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teachers</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Teacher List</h1>

        <div>
            <form method="GET" class="flex items-center space-x-2">
    <input type="text" name="search" placeholder="Search by name or email..."
           value="<?php echo htmlspecialchars($search ?? ''); ?>"
           class="px-3 py-2 border border-gray-300 rounded w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Search</button>
</form>

        </div>
            <div class="space-x-2">
                <a href="../exports/export_csv.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export CSV</a>
                <a href="../exports/export_pdf.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Export PDF</a>
                <a href="add_teacher.php" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ Add Teacher</a>
            </div>
        </div>

        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-3">#</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Department</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="p-3"><?php echo $row['id']; ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="p-3"><?php echo $row['department']; ?></td>
                    <td class="p-3">
                        <a href="edit_teachers.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                        <a href="delete_teacher.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this teacher?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- === Pagination Links === -->
        <div class="mt-4 flex justify-center space-x-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="px-3 py-1 <?php echo ($i == $page) ? 'bg-indigo-600 text-white' : 'bg-gray-200'; ?> rounded hover:bg-indigo-500">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Next</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>