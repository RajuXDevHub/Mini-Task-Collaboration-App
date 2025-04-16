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
$id = sanitizeInput($_POST['id'] ?? '');
$title = sanitizeInput($_POST['title'] ?? '');
$deadline = sanitizeInput($_POST['deadline'] ?? '');
$priority = sanitizeInput($_POST['priority'] ?? '');
$status = sanitizeInput($_POST['status'] ?? '');

if (empty($id)) $errors[] = 'Task ID is required';
if (empty($title)) $errors[] = 'Title is required';
if (empty($deadline)) $errors[] = 'Deadline is required';
if (empty($priority)) $errors[] = 'Priority is required';
if (empty($status)) $errors[] = 'Status is required';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Check if user owns the task or is admin
$stmt = $pdo->prepare("SELECT user_id FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    echo json_encode(['success' => false, 'message' => 'Task not found']);
    exit;
}

if ($task['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Not authorized to update this task']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, deadline = ?, priority = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $deadline, $priority, $status, $id]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>