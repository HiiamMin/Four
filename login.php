<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Đăng nhập</title>
</head>

<body>
    <?php
    session_start();
    include_once 'connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['loginUsername'];
        $password = $_POST['loginPassword'];

        // Kiểm tra đăng nhập admin với mật khẩu cứng
        $admin_hardcoded_password = '123456'; // Thay bằng mật khẩu mạnh hơn
        if ($username === 'admin' && password_verify($password, password_hash($admin_hardcoded_password, PASSWORD_DEFAULT))) {
            $_SESSION['user'] = array('username' => 'admin', 'role' => 'admin');
            header("Location: home.php");
            exit();
        } else {
            // Kiểm tra người dùng thông thường trong cơ sở dữ liệu
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // Kiểm tra xem người dùng tồn tại và mật khẩu đúng
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: home.php");
                exit();
            } else {
                $_SESSION['error'] = "Tên người dùng hoặc mật khẩu không đúng!";
            }
        }
    }
    ?>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Đăng nhập</h2>
            <div class="form-control">
                <label for="loginUsername">Username</label>
                <input type="text" id="loginUsername" name="loginUsername" placeholder="Nhập tên đăng nhập của bạn" required>
            </div>
            <div class="form-control">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="loginPassword" placeholder="Nhập mật khẩu" required>
            </div>
            <button type="submit" class="btn">Đăng nhập</button>
            <p class="login-link">Bạn chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
            <p class="login-link">Bạn quên mật khẩu? <a href="forgot_password.php">Quên mật khẩu</a></p>
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message'>{$_SESSION['error']}</p>";
            unset($_SESSION['error']);
        }
        ?>
    </div>
</body>

</html>