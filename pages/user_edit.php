<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit();
}
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, full_name = ?, role = ?, password = ? WHERE id = ?');
        $stmt->execute([$username, $email, $full_name, $role, $password, $id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, full_name = ?, role = ? WHERE id = ?');
        $stmt->execute([$username, $email, $full_name, $role, $id]);
    }
    header('Location: users.php');
    exit();
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: users.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
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

        .btn-primary {
            background: linear-gradient(90deg, #36d1c4 0%, #5b86e5 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px rgba(44, 83, 100, 0.15);
            transition: background 0.2s, transform 0.2s;
        }

        .btn-primary:hover {
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
    <?php $activePage = 'users';
    include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="glass-card">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z" />
                </svg>
            </div>
            <div class="form-title">Edit User</div>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control"
                        value="<?php echo htmlspecialchars($user['full_name']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="team" <?php if ($user['role'] === 'team')
                            echo 'selected'; ?>>Team</option>
                        <option value="admin" <?php if ($user['role'] === 'admin')
                            echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-2">Update User</button>
                <a href="users.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</body>

</html>