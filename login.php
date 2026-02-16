<?php
session_start();
include 'config/database.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $db->escape_string($_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header i {
            font-size: 60px;
            color: #667eea;
            margin-bottom: 20px;
        }

        .login-header h2 {
            color: #333;
            font-weight: 600;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            color: white;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-building"></i>
            <h2>MTsN Ngada</h2>
            <p class="text-muted">Sistem Akademik</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">
                <i class="bi bi-info-circle"></i> Demo: admin / admin123
            </small>
        </div>
    </div>
</body>

</html>