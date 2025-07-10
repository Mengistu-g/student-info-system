<?php
session_start();
include "../conn.php";  // Adjust the path if needed

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Check if department ID is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $department_id = (int)$_GET['id'];

    // Optionally, check if there are students or teachers linked to this department before deleting
    // Example (uncomment if needed):
    
    $check = $conn->prepare("SELECT COUNT(*) as total FROM students WHERE department_id = ?");
    $check->bind_param("i", $department_id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();
    if ($result['total'] > 0) {
        $_SESSION['error'] = "Cannot delete: This department is linked to one or more students.";
        header("Location: departments.php");
        exit;
    }
    
    // Delete the department
    $stmt = $conn->prepare("DELETE FROM departments WHERE id = ?");
    $stmt->bind_param("i", $department_id);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: departments.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting department.";
    }
} else {
    echo "Invalid department ID.";
}
?>