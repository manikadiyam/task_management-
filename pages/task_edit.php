<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: tasks.php');
    exit();
}
$id = $_GET['id'];

// Fetch all users for assignment
$all_users = $pdo->query('SELECT id, username, full_name FROM users')->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $assigned_user_id = $_POST['assigned_user_id'];
    // Remove status update from here, only allow via follow-up
    $stmt = $pdo->prepare('UPDATE tasks SET user_id = ?, title = ?, description = ?, due_date = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$assigned_user_id, $title, $description, $due_date, $id]);
    header('Location: tasks.php');
    exit();
}

$stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$task) {
    header('Location: tasks.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="/task-manager/assets/sidebar.css" rel="stylesheet">
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 0;
            animation: fadeIn 1s;
            max-width: 480px;
            width: 100%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 8px rgba(44, 83, 100, 0.08);
            background: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c5364;
        }

        .btn-success {
            background: linear-gradient(90deg, #36d1c4 0%, #5b86e5 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(44, 83, 100, 0.15);
            transition: background 0.2s, transform 0.2s;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #5b86e5 0%, #36d1c4 100%);
            transform: translateY(-2px) scale(1.03);
        }

        .btn-secondary {
            border-radius: 12px;
        }

        .form-title {
            font-size: 1.7rem;
            font-weight: 700;
            color: #2c5364;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .icon {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .icon svg {
            width: 40px;
            height: 40px;
            fill: #36d1c4;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <?php $activePage = 'tasks';
    include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="glass-card">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H7v-2h5v2zm5-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
            </div>
            <div class="form-title">Edit Task</div>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Assign To</label>
                    <select name="assigned_user_id" class="form-control" required>
                        <option value="">Select User</option>
                        <?php foreach ($all_users as $user): ?>
                            <option value="<?php echo $user['id']; ?>" <?php if ($task['user_id'] == $user['id'])
                                   echo 'selected'; ?>>
                                <?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control"
                        value="<?php echo htmlspecialchars($task['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                        class="form-control"><?php echo htmlspecialchars($task['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control"
                        value="<?php echo htmlspecialchars($task['due_date']); ?>">
                </div>
                <button type="submit" class="btn btn-success w-100 mt-2">Update Task</button>
                <a href="tasks.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</body>

</html>