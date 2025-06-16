<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/config.php';

// Handle user CRUD actions (add, edit, delete)
// ...existing code...

// Fetch all users
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/assets/sidebar.css" rel="stylesheet">
</head>

<body>
    <?php $activePage = 'users';
    include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="card-glass">
            <h2>User Management</h2>
            <a href="user_add.php" class="btn btn-success mb-2">Add User</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                            <td>
                                <a href="user_edit.php?id=<?php echo $user['id']; ?>"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <a href="user_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>