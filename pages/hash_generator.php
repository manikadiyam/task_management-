<?php
require_once '../includes/config.php';
// Usage: Open this file in your browser and enter a password to get its hash.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<strong>Hash:</strong> <code>$hash</code>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Password Hash Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h3 class="mb-3">Generate Password Hash</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate Hash</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>