<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] === 'admin') {
    // Admin: show all tasks
    $stmt = $pdo->query('SELECT t.*, u.username, (
        SELECT MIN(next_followup_date) FROM task_followups f WHERE f.task_id = t.id AND next_followup_date IS NOT NULL
    ) as next_followup
    FROM tasks t
    JOIN users u ON t.user_id = u.id
    WHERE t.status != "completed"
    ORDER BY 
        CASE WHEN next_followup IS NULL THEN 1 ELSE 0 END, 
        next_followup ASC, t.due_date ASC');
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Team: show only their tasks
    $stmt = $pdo->prepare('SELECT t.*, u.username, (
        SELECT MIN(next_followup_date) FROM task_followups f WHERE f.task_id = t.id AND next_followup_date IS NOT NULL
    ) as next_followup
    FROM tasks t
    JOIN users u ON t.user_id = u.id
    WHERE t.status != "completed" AND t.user_id = ?
    ORDER BY 
        CASE WHEN next_followup IS NULL THEN 1 ELSE 0 END, 
        next_followup ASC, t.due_date ASC');
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php $activePage = 'tasks';
    include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="card-glass">
            <h2>Task Management</h2>
            <a href="task_add.php" class="btn btn-success mb-2">Add Task</a>
            <a href="tasks_completed.php" class="btn btn-info mb-2">View Completed Tasks</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Next Follow-up</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo $task['id']; ?></td>
                            <td><?php echo htmlspecialchars($task['title']); ?></td>
                            <td><?php echo htmlspecialchars($task['description']); ?></td>
                            <td><?php echo htmlspecialchars($task['status']); ?></td>
                            <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                            <td><?php echo $task['next_followup'] ? htmlspecialchars($task['next_followup']) : '-'; ?></td>
                            <td>
                                <a href="task_edit.php?id=<?php echo $task['id']; ?>"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <a href="task_delete.php?id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this task?');">Delete</a>
                                <a href="task_followup.php?id=<?php echo $task['id']; ?>"
                                    class="btn btn-info btn-sm">Follow-up</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>