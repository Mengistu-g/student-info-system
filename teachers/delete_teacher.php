<?php
session_start();
include "../conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
   // header("Location: teachers.php");
    exit;
}

// Fetch teacher's user_id
$stmt = $conn->prepare("SELECT user_id FROM teachers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $teacher = $res->fetch_assoc();
    $user_id = $teacher['user_id'];

    // Delete teacher
    $conn->query("DELETE FROM teachers WHERE id = $id");

    // Delete corresponding user account
    $conn->query("DELETE FROM users WHERE id = $user_id");
   
}
 //header("Location: teachers.php");

exit; 
