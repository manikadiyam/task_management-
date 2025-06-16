<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($user_role !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Fetch team members for filter
$team_members = $pdo->query("SELECT id, full_name, username FROM users WHERE role = 'team'")->fetchAll(PDO::FETCH_ASSOC);

// Handle filter
$where = "WHERE 1=1";
$params = [];
if (!empty($_GET['team_id'])) {
    $where .= " AND t.user_id = ?";
    $params[] = $_GET['team_id'];
}
if (!empty($_GET['status'])) {
    $where .= " AND t.status = ?";
    $params[] = $_GET['status'];
}
if (!empty($_GET['from_date'])) {
    $where .= " AND t.created_at >= ?";
    $params[] = $_GET['from_date'];
}
if (!empty($_GET['to_date'])) {
    $where .= " AND t.created_at <= ?";
    $params[] = $_GET['to_date'];
}

$stmt = $pdo->prepare("SELECT t.*, u.full_name, u.username FROM tasks t JOIN users u ON t.user_id = u.id $where ORDER BY t.created_at DESC");
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

function statusLabel($status)
{
    switch ($status) {
        case 'pending':
            return '<span class="badge bg-warning">Pending</span>';
        case 'in_progress':
            return '<span class="badge bg-info">In Progress</span>';
        case 'on_hold':
            return '<span class="badge bg-secondary">On Hold</span>';
        case 'completed':
            return '<span class="badge bg-success">Completed</span>';
        default:
            return $status;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
    <?php $activePage = 'reports';
    include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="card-glass mb-4">
            <h2>Task Reports</h2>
            <form class="row g-3 mb-3" method="get">
                <div class="col-md-3">
                    <label class="form-label">Team Member</label>
                    <select name="team_id" class="form-control">
                        <option value="">All</option>
                        <?php foreach ($team_members as $member): ?>
                            <option value="<?php echo $member['id']; ?>" <?php if (!empty($_GET['team_id']) && $_GET['team_id'] == $member['id'])
                                   echo 'selected'; ?>>
                                <?php echo htmlspecialchars($member['full_name'] ?: $member['username']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="pending" <?php if (!empty($_GET['status']) && $_GET['status'] == 'pending')
                            echo 'selected'; ?>>Pending</option>
                        <option value="in_progress" <?php if (!empty($_GET['status']) && $_GET['status'] == 'in_progress')
                            echo 'selected'; ?>>In Progress</option>
                        <option value="on_hold" <?php if (!empty($_GET['status']) && $_GET['status'] == 'on_hold')
                            echo 'selected'; ?>>On Hold</option>
                        <option value="completed" <?php if (!empty($_GET['status']) && $_GET['status'] == 'completed')
                            echo 'selected'; ?>>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control"
                        value="<?php echo htmlspecialchars($_GET['from_date'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control"
                        value="<?php echo htmlspecialchars($_GET['to_date'] ?? ''); ?>">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-1 align-self-end">
                    <button type="button" class="btn btn-success w-100" id="exportBtn">Export</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="reportTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Due Date</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo $task['id']; ?></td>
                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                <td><?php echo statusLabel($task['status']); ?></td>
                                <td><?php echo htmlspecialchars($task['full_name'] ?: $task['username']); ?></td>
                                <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                                <td><?php echo htmlspecialchars($task['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#reportTable').DataTable({
                dom: 'Bfrtip',
                buttons: []
            });
            $('#exportBtn').on('click', function () {
                var csv = [];
                var rows = document.querySelectorAll('#reportTable tr');
                for (var i = 0; i < rows.length; i++) {
                    var row = [], cols = rows[i].querySelectorAll('th, td');
                    for (var j = 0; j < cols.length; j++)
                        row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
                    csv.push(row.join(","));
                }
                // Download CSV
                var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
                var downloadLink = document.createElement("a");
                downloadLink.download = "task_report.csv";
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.style.display = "none";
                document.body.appendChild(downloadLink);
                downloadLink.click();
            });
        });
    </script>
</body>

</html>