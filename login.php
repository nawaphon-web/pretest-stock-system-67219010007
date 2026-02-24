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
                // Password is correct
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
    <title>Login - TechStock System</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="logo-icon" style="color: var(--neon-blue); animation: glow-pulse 3s infinite;">
            <i class="fa-solid fa-microchip"></i>
        </div>
        <h2 class="shimmer-text" style="font-size: 2.5rem; letter-spacing: -2px;">TECHSTOCK</h2>
        <p class="subtitle" style="text-transform: uppercase; letter-spacing: 2px;">เข้าสู่ระบบสมาชิก</p>

        <?php if ($error): ?>
            <div class="alert"
                style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username" style="color: var(--primary-color);">ชื่อผู้ใช้งาน (USERNAME)</label>
                <input type="text" id="username" name="username" placeholder="Username" required
                    style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: white; padding: 1rem; border-radius: 0.75rem; transition: 0.3s;"
                    onfocus="this.style.borderColor='var(--primary-color)'"
                    onblur="this.style.borderColor='var(--glass-border)'">
            </div>
            <div class="form-group">
                <label for="password" style="color: var(--primary-color);">รหัสผ่าน (PASSWORD)</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required
                    style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: white; padding: 1rem; border-radius: 0.75rem; transition: 0.3s;"
                    onfocus="this.style.borderColor='var(--primary-color)'"
                    onblur="this.style.borderColor='var(--glass-border)'">
            </div>
            <button type="submit" class="cyber-btn" style="width: 100%; margin-top: 1rem;">เข้าสู่ระบบ</button>
        </form>

        <div class="footer-link"
            style="margin-top: 2rem; border-top: 1px solid var(--glass-border); padding-top: 2rem;">
            <p style="color: var(--text-muted);">ระบบความปลอดภัยขั้นสูง <br><a href="#"
                    style="color: var(--primary-color); text-decoration: none;">ลืมรหัสผ่าน?</a></p>
        </div>
    </div>
</body>

</html>