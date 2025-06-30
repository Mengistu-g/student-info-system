<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$conn->query("DELETE FROM courses WHERE id = $id");

header("Location: courses.php");
exit;



// <td class="border px-4 py-2">
//     <a href="edit_course.php?id=<?= $row['id'] ?>" class="text-blue-600">Edit</a> |
//     <a href="delete_course.php?id=<?= $row['id'] ?>" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</a>
// </td>

?>


