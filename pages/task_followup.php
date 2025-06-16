<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '/pages/task_followup.php?id=' . $task_id);
    exit();
}
$task_id = $_GET['id'];

// Handle AJAX followup submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $comment = $_POST['comment'];
    $status = $_POST['status'];
    $next_followup_date = $_POST['next_followup_date'] ?: null;
    $stmt = $pdo->prepare('INSERT INTO task_followups (task_id, comment, status, next_followup_date) VALUES (?, ?, ?, ?)');
    $stmt->execute([$task_id, $comment, $status, $next_followup_date]);
    // Update main task status to match followup status
    $stmt = $pdo->prepare('UPDATE tasks SET status = ? WHERE id = ?');
    $stmt->execute([$status, $task_id]);
    echo 'success';
    exit();
}

// Fetch followups
$stmt = $pdo->prepare('SELECT * FROM task_followups WHERE task_id = ? ORDER BY created_at DESC');
$stmt->execute([$task_id]);
$followups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch task info
$stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
$stmt->execute([$task_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$task) {
    header('Location: ' . BASE_URL . '/pages/task_followup.php?id=' . $task_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Task Follow-up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/assets/sidebar.css" rel="stylesheet">
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.17);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 2.5rem 2rem 2rem 2rem;
            margin-top: 0;
            animation: fadeIn 1s;
            width: 100%;
            max-width: none;
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

        .btn-info {
            background: linear-gradient(90deg, #36d1c4 0%, #5b86e5 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #fff;
            box-shadow: 0 2px 8px rgba(44, 83, 100, 0.15);
            transition: background 0.2s, transform 0.2s;
        }

        .btn-info:hover {
            background: linear-gradient(90deg, #5b86e5 0%, #36d1c4 100%);
            color: #fff;
            transform: translateY(-2px) scale(1.03);
        }

        .btn-secondary {
            border-radius: 12px;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c5364;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .main-content {
            min-height: 100vh;
        }

        .list-group-item {
            border-radius: 10px;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.8);
        }

        .back-btn {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <?php $activePage = 'tasks';
    include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="glass-card">
            <a href="tasks.php" class="btn btn-secondary back-btn">&larr; Back to Task List</a>
            <div class="form-title mb-3">Task Follow-up: <?php echo htmlspecialchars($task['title']); ?></div>
            <form id="followupForm" class="mb-4">
                <div class="mb-3">
                    <label class="form-label">Add Comment</label>
                    <textarea name="comment" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Next Follow-up Date</label>
                    <input type="date" name="next_followup_date" class="form-control">
                </div>
                <button type="submit" class="btn btn-info w-100">Add Follow-up</button>
            </form>
            <h4 class="mt-4 mb-2">Follow-up History</h4>
            <ul class="list-group" id="followupList">
                <?php foreach ($followups as $f): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($f['created_at']); ?>:</strong>
                        <span class="badge bg-secondary ms-2">Status: <?php echo htmlspecialchars($f['status']); ?></span>
                        <?php if ($f['next_followup_date']): ?>
                            <span class="badge bg-info ms-2">Next:
                                <?php echo htmlspecialchars($f['next_followup_date']); ?></span>
                        <?php endif; ?>
                        <div><?php echo htmlspecialchars($f['comment']); ?></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script>
        // AJAX submit for followup
        const form = document.getElementById('followupForm');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            formData.append('ajax', '1');
            fetch('', {
                method: 'POST',
                body: formData
            })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        location.reload();
                    }
                });
        });
    </script>
</body>

</html>