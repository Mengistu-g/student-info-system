<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$msg = "";

// Fetch departments and teachers for dropdowns
$departments = $conn->query("SELECT * FROM departments");
$teachers = $conn->query("SELECT * FROM teachers");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $department_id = $_POST['department_id'];
    $teacher_id = $_POST['teacher_id'];

    // Insert user (for login, with role = student)
    $password = password_hash("student123", PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$fullname', '$email', '$password', 'student')");
    $user_id = $conn->insert_id;

    // Insert student
    $sql = "INSERT INTO students (fullname, email, department_id, teacher_id, user_id)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $fullname, $email, $department_id, $teacher_id, $user_id);
    $stmt->execute();

    $msg = "Student added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Add Student</h2>
        <?php if ($msg): ?>
            <p class="text-green-600 mb-4"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required class="w-full mb-4 p-3 border rounded" />
            <input type="email" name="email" placeholder="Email" required class="w-full mb-4 p-3 border rounded" />
            
            <select name="department_id" required class="w-full mb-4 p-3 border rounded">
                <option value="">Select Department</option>
                <?php while($dept = $departments->fetch_assoc()): ?>
                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <select name="teacher_id" required class="w-full mb-4 p-3 border rounded">
                <option value="">Select Teacher</option>
                <?php while($teacher = $teachers->fetch_assoc()): ?>
                    <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Student</button>
        </form>

        <p class="mt-4">
            <a href="students.php" class="text-blue-600 hover:underline">← Back to Student List</a>
        </p>
    </div>
</body>
</html>