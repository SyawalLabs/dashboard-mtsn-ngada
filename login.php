<?php
session_start();
include 'config/database.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $db->escape_string($_POST['username']);
    $password = md5($_POST['password']); // Note: md5 tidak direkomendasikan untuk produksi

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap']; // Tambahkan jika ada kolom nama
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
    <title>Login - Sistem Akademik MTsN Ngada</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #1a5f3e 0%, #2e7d32 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            border-top: 5px solid #ffc107;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi background */
        .login-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(26, 95, 62, 0.03);
            border-radius: 50%;
            z-index: 0;
        }

        .login-card::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 193, 7, 0.03);
            border-radius: 50%;
            z-index: 0;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .login-header i {
            font-size: 70px;
            color: #1a5f3e;
            margin-bottom: 15px;
            background: #f0f7f0;
            padding: 20px;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(26, 95, 62, 0.2);
        }

        .login-header h2 {
            color: #1a5f3e;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 28px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .login-header p span {
            color: #ffc107;
            font-weight: 600;
        }

        .login-form {
            position: relative;
            z-index: 1;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #1a5f3e;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a5f3e;
            box-shadow: 0 0 0 3px rgba(26, 95, 62, 0.1);
            outline: none;
        }

        .form-control:focus+.input-group-text {
            border-color: #1a5f3e;
        }

        .btn-login {
            background: linear-gradient(135deg, #1a5f3e 0%, #2e7d32 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            color: white;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            margin: 10px 0 20px 0;
            font-size: 16px;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
            z-index: -1;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 95, 62, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Alert styling */
        .alert {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
            position: relative;
            z-index: 1;
            border: none;
        }

        .alert-danger {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            color: #dc2626;
        }

        .alert-danger i {
            color: #dc2626;
        }

        .alert-success {
            background: #f0f7f0;
            border-left: 4px solid #1a5f3e;
            color: #1a5f3e;
        }

        /* Footer styling */
        .madrasah-footer {
            text-align: center;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        .madrasah-footer small {
            color: #999;
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }

        .madrasah-footer strong {
            color: #1a5f3e;
            font-weight: 600;
        }

        .madrasah-footer .kemenag-text {
            color: #ffc107;
            font-weight: 600;
        }

        .demo-badge {
            background: #f0f7f0;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
        }

        .demo-badge small {
            color: #1a5f3e;
            margin: 0;
        }

        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #1a5f3e;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <!-- <i class="bi bi-building"></i> -->
            <h2>MTsN Ngada</h2>
            <p>Sistem Akademik <br><span>Madrasah Tsanawiyah Negeri Ngada</span></p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                Anda telah berhasil logout.
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="login-form" id="loginForm">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person-fill"></i>
                </span>
                <input type="text" name="username" class="form-control"
                    placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    required autofocus>
            </div>

            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock-fill"></i>
                </span>
                <input type="password" name="password" class="form-control" id="password"
                    placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword()">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </span>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="color: #666; font-size: 14px;">
                        Ingat saya
                    </label>
                </div>
                <a href="#" class="forgot-password" style="color: #1a5f3e; text-decoration: none; font-size: 14px;" onclick="forgotPassword()">
                    <i class="bi bi-question-circle"></i> Lupa Password?
                </a>
            </div>

            <button type="submit" class="btn-login" id="loginButton">
                <i class="bi bi-box-arrow-in-right me-2"></i>MASUK
            </button>

            <!-- Demo credentials info -->
            <div class="demo-badge text-center">
                <small>
                    <i class="bi bi-info-circle-fill me-1" style="color: #1a5f3e;"></i>
                    Akun Demo: <strong>admin</strong> / <strong>admin123</strong>
                </small>
            </div>
        </form>

        <div class="madrasah-footer">
            <div class="d-flex justify-content-center gap-3 mb-2">
                <small>
                    <i class="bi bi-geo-alt-fill" style="color: #1a5f3e;"></i>
                    Jl. Pendidikan No. 1, Ngada
                </small>
                <small>
                    <i class="bi bi-telephone-fill" style="color: #1a5f3e;"></i>
                    (0384) 12345
                </small>
            </div>
           
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 (optional, untuk notifikasi yang lebih bagus) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }

        // Forgot password handler
        function forgotPassword() {
            Swal.fire({
                icon: 'info',
                title: 'Lupa Password?',
                html: `
                    <p>Silakan hubungi administrator untuk mereset password:</p>
                    <div class="text-start mt-3">
                        <small><i class="bi bi-envelope me-2"></i> admin@mtsnngada.sch.id</small><br>
                        <small><i class="bi bi-telephone me-2"></i> 0812-3456-7890</small>
                    </div>
                `,
                confirmButtonColor: '#1a5f3e'
            });
        }

        // Loading state on form submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.querySelector('input[name="username"]').value.trim();
            const password = document.querySelector('input[name="password"]').value;

            if (!username || !password) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Username dan password harus diisi!',
                    confirmButtonColor: '#1a5f3e'
                });
                return;
            }

            // Tampilkan loading pada button
            const loginBtn = document.getElementById('loginButton');
            loginBtn.innerHTML = '<span class="spinner me-2"></span>Memproses...';
            loginBtn.disabled = true;
        });

        // Auto-hide alert after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);

        // Cek dan isi form jika ada cookie remember me (jika diimplementasikan)
        <?php if (isset($_COOKIE['remember_username'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('input[name="username"]').value = '<?php echo $_COOKIE['remember_username']; ?>';
                document.getElementById('remember').checked = true;
            });
        <?php endif; ?>
    </script>
</body>

</html>