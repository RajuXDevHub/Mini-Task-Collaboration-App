<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

header('Content-Type: application/json');

$errors = [];
$title = sanitizeInput($_POST['title'] ?? '');
$deadline = sanitizeInput($_POST['deadline'] ?? '');
$priority = sanitizeInput($_POST['priority'] ?? '');

if (empty($title)) $errors[] = 'Title is required';
if (empty($deadline)) $errors[] = 'Deadline is required';
if (empty($priority)) $errors[] = 'Priority is required';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, deadline, priority) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $deadline, $priority]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>