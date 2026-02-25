<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="login-screen">
    <div class="login-card animate-fade-in">
        <div style="margin-bottom: 2rem; color: var(--primary-color);">
            <i class="fa-solid fa-microchip" style="font-size: 4rem;"></i>
        </div>
        <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; color: #1e293b;">TECHSTOCK</h2>
        <p style="color: var(--text-muted); margin-bottom: 2.5rem;">ระบบบริหารจัดการสินค้าและจัดสเปค</p>

        <?php if ($error): ?>
            <div
                style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem; font-size: 0.875rem; text-align: left;">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required
                    autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required
                    autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; height: 3.5rem; font-size: 1.125rem;">
                เข้าสู่ระบบ
            </button>
        </form>

        <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
            <a href="#"
                style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 0.875rem;">ลืมรหัสผ่าน?</a>
        </div>
    </div>
</body>

</html>