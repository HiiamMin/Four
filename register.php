<?php
session_start();
include_once 'connection.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Kiểm tra xem các trường đã được điền đầy đủ
    if (empty($username)) {
        $_SESSION['error_username'] = "Vui lòng nhập tên đăng nhập!";
    }
    if (empty($email)) {
        $_SESSION['error_email'] = "Vui lòng nhập địa chỉ email!";
    }
    if (empty($password)) {
        $_SESSION['error_password'] = "Vui lòng nhập mật khẩu!";
    } elseif (strlen($password) < 6) {
        $_SESSION['error_password'] = "Mật khẩu phải có ít nhất 6 ký tự!";
    }
    if (empty($confirmPassword)) {
        $_SESSION['error_confirmPassword'] = "Vui lòng xác nhận mật khẩu!";
    } elseif ($password !== $confirmPassword) {
        $_SESSION['error_confirmPassword'] = "Mật khẩu và mật khẩu xác nhận không khớp!";
    }

    // Nếu có lỗi, không thực hiện đăng ký
    if (isset($_SESSION['error_username']) || isset($_SESSION['error_email']) || isset($_SESSION['error_password']) || isset($_SESSION['error_confirmPassword'])) {
        $_SESSION['error'] = "Vui lòng sửa các lỗi bên trên!";
    } else {
        // Kiểm tra xem tên người dùng đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $_SESSION['error_username'] = "Tên đăng nhập này đã được sử dụng!";
        } else {
            // Hash mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng mới vào cơ sở dữ liệu với vai trò user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$username, $email, $hashed_password]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "Đăng ký thành công!";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Đăng ký không thành công!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Đăng ký</title>
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Đăng ký</h2>
            <div class="form-control">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập của bạn">
                <?php if (isset($_SESSION['error_username'])) {
                    echo "<span class='error-message'>{$_SESSION['error_username']}</span>";
                    unset($_SESSION['error_username']);
                } ?>
            </div>
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email của bạn">
                <?php if (isset($_SESSION['error_email'])) {
                    echo "<span class='error-message'>{$_SESSION['error_email']}</span>";
                    unset($_SESSION['error_email']);
                } ?>
            </div>
            <div class="form-control">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
                <?php if (isset($_SESSION['error_password'])) {
                    echo "<span class='error-message'>{$_SESSION['error_password']}</span>";
                    unset($_SESSION['error_password']);
                } ?>
            </div>
            <div class="form-control">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu">
                <?php if (isset($_SESSION['error_confirmPassword'])) {
                    echo "<span class='error-message'>{$_SESSION['error_confirmPassword']}</span>";
                    unset($_SESSION['error_confirmPassword']);
                } ?>
            </div>
            <button type="submit" class="btn">Đăng ký</button>
            <br>
            <br>
            <p class="login-link">Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
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