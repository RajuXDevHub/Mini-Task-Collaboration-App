<?php
session_start();
require_once '../config/database.php';
require_once '../config/functions.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

header('Content-Type: application/json');

$id = sanitizeInput($_POST['id'] ?? '');

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'Task ID is required']);
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
    echo json_encode(['success' => false, 'message' => 'Not authorized to delete this task']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>