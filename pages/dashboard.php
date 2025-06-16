<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$teamStats = [];
$overall = [];

if ($user['role'] === 'admin') {
    // Admin: Team performance statistics
    // 1. Task count per user
    $teamStats = $pdo->query('SELECT u.full_name, u.username, COUNT(t.id) as total_tasks, 
        SUM(t.status = "completed") as completed_tasks, 
        SUM(t.status != "completed") as pending_tasks
        FROM users u
        LEFT JOIN tasks t ON t.user_id = u.id
        WHERE u.role = "team"
        GROUP BY u.id
        ORDER BY total_tasks DESC')->fetchAll(PDO::FETCH_ASSOC);
    // 2. Overall stats
    $overall = $pdo->query('SELECT COUNT(*) as total, SUM(status = "completed") as completed, SUM(status != "completed") as pending FROM tasks')->fetch(PDO::FETCH_ASSOC);
}

// Get dashboard stats
$stats = [];
for ($i = 1; $i <= 12; $i++) {
    $month = sprintf('%02d', $i);
    $year = date('Y');
    $completed = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'completed' AND MONTH(created_at) = ? AND YEAR(created_at) = ?");
    $completed->execute([$user_id, $month, $year]);
    $pending = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'pending' AND MONTH(created_at) = ? AND YEAR(created_at) = ?");
    $pending->execute([$user_id, $month, $year]);
    $stats[] = [
        'month' => $month,
        'completed' => $completed->fetchColumn(),
        'pending' => $pending->fetchColumn()
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/assets/sidebar.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e3e9f7 100%);
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 220px;
            background: #fff;
            color: #2c5364;
            transition: width 0.3s;
            z-index: 1000;
            box-shadow: 2px 0 12px rgba(44, 83, 100, 0.08);
            border-right: 1px solid #e3e9f7;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar-header {
            padding: 1.5rem 1rem 1rem 1.5rem;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e3e9f7;
            transition: opacity 0.3s, height 0.3s, padding 0.3s;
        }

        .sidebar.collapsed .sidebar-header span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: #2c5364;
            font-size: 1.5rem;
            cursor: pointer;
            margin-left: 0;
        }

        .sidebar-menu {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            margin-top: 2rem;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .sidebar-menu {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu ul li {
            margin-bottom: 1.2rem;
        }

        .sidebar-menu ul li a {
            color: #2c5364;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 0.7rem 1.5rem;
            display: block;
            border-radius: 8px 0 0 8px;
            transition: background 0.2s, color 0.2s, opacity 0.2s;
        }

        .sidebar-menu ul li a.active,
        .sidebar-menu ul li a:hover {
            background: linear-gradient(90deg, #36d1c4 0%, #5b86e5 100%);
            color: #fff;
        }

        .main-content {
            margin-left: 220px;
            padding: 2.5rem 2rem 2rem 2rem;
            transition: margin-left 0.3s;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 60px;
        }

        .dashboard-header {
            color: #2c5364;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid #e3e9f7;
            padding: 2rem;
        }

        @media (max-width: 900px) {

            .sidebar,
            .sidebar.collapsed {
                width: 100vw;
                height: auto;
                position: relative;
            }

            .main-content,
            .sidebar.collapsed~.main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <?php $activePage = 'dashboard';
    include 'sidebar.php'; ?>
    <div class="main-content" id="mainContent">
        <div class="dashboard-header">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></div>
        <?php if ($user['role'] === 'admin'): ?>
            <div class="card-glass mb-4">
                <h4>Team Performance Overview</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-success text-white rounded mb-2">
                            <strong>Total Tasks:</strong> <?php echo $overall['total']; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-info text-white rounded mb-2">
                            <strong>Completed:</strong> <?php echo $overall['completed']; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-warning text-dark rounded mb-2">
                            <strong>Pending:</strong> <?php echo $overall['pending']; ?>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Team Member</th>
                            <th>Total Tasks</th>
                            <th>Completed</th>
                            <th>Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teamStats as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['full_name'] ?: $member['username']); ?></td>
                                <td><?php echo $member['total_tasks']; ?></td>
                                <td><?php echo $member['completed_tasks']; ?></td>
                                <td><?php echo $member['pending_tasks']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <div class="card-glass">
            <canvas id="taskChart" height="100"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
        const stats = <?php echo json_encode($stats); ?>;
        const labels = stats.map(s => s.month);
        const completed = stats.map(s => s.completed);
        const pending = stats.map(s => s.pending);
        const ctx = document.getElementById('taskChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Completed',
                        data: completed,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)'
                    },
                    {
                        label: 'Pending',
                        data: pending,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Monthly Task Status' }
                }
            }
        });
    </script>
</body>

</html>