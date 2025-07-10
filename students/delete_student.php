<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: students/students.php");
    exit;
}

// Get student record
$stmt = $conn->prepare("SELECT user_id FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $student = $res->fetch_assoc();
    $user_id = $student['user_id'];

    // Delete student
    $conn->query("DELETE FROM students WHERE id = $id");

    // Delete associated user account
    $conn->query("DELETE FROM users WHERE id = $user_id");
}

header("Location: students/students.php");
exit;