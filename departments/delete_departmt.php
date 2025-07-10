<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: departments/departments.php");
    exit;
}

// Optional: You might want to check if any students or teachers are linked to this department before deleting

$conn->query("DELETE FROM departments WHERE id = $id");

header("Location: departments/departments.php");
exit;