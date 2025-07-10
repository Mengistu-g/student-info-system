<?php
session_start();
include "../conn.php";  // Adjust path if needed

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Check if id is provided via GET and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = (int)$_GET['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        // Redirect to students list page with success message
        header("Location: students.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting student record.";
    }
} else {
    echo "Invalid student ID.";
}
?>