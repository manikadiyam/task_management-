<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

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

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
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
            width: 48px;
            height: 48px;
            fill: #36d1c4;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="glass-card col-12 col-sm-8 col-md-5 col-lg-4 mx-auto">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z" />
            </svg>
        </div>
        <div class="login-title">Sign In</div>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <form method="POST" action="login_action.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
        </form>
    </div>
</body>

</html>