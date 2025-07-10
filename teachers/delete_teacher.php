<?php
session_start();
include "../conn.php";  // Adjust if conn.php is in a different location

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Check if id is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $teacher_id = (int)$_GET['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $teacher_id);

    if ($stmt->execute()) {
        // Redirect back to teachers list with a success message
        header("Location: teachers.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting teacher record.";
    }
} else {
    echo "Invalid teacher ID.";
}
?>