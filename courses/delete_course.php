<?php
session_start();
include "../conn.php";  // Adjust this path if needed

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Check if course id is provided via GET and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $course_id = (int)$_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        // Redirect to courses list with success message
        header("Location: courses.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting course.";
    }
} else {
    echo "Invalid course ID.";
}
?>