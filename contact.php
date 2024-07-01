<?php
session_start(); // Nếu session chưa được bắt đầu trong các tập tin khác

// Xử lý gửi email khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Thiết lập địa chỉ email nhận
    $to = 'taintpk03556@fpt.edu.vn';

    // Tiêu đề email
    $subject = 'Thông điệp từ trang liên hệ';

    // Nội dung email
    $email_content = "Bạn nhận được một thông điệp từ $name ($email):\n\n$message";

    // Tiêu đề và nội dung email
    $headers = "From: $email";

    // Gửi email
    if (mail($to, $subject, $email_content, $headers)) {
        $_SESSION['message'] = "Email của bạn đã được gửi thành công!";
    } else {
        $_SESSION['error_message'] = "Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.";
    }

    // Điều hướng người dùng về trang contact.php
    header("Location: contact.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Liên Hệ - Shop Trái Cây</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://kit.fontawesome.com/7f10d10fe3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="index.css">
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        header {
        background-color: #333; 
        color: white; 
        padding: 10px 0; 
    }

    .menu {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .menu-item {
        display: inline-block;
        margin-right: 10px;
    }

    .menu-item a {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        display: block;
    }

    .menu-item:hover {
        background-color: #555; /* Màu nền khi di chuột qua */
    }

    .sub-menu {
        display: none;
        position: absolute;
        background-color: #333;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .sub-menu li {
        display: block;
    }

    .sub-menu li a {
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        display: block;
    }

    .sub-menu li a:hover {
        background-color: #555;
    }
    .container {
        
    }

    </style>
</head>

<body>
    <header>
        <div class="container">
            
            </a>
            <nav>
                <ul class="menu">
                    <li class="menu-item"><a href="home.php">Trang chủ</a></li>
                    <li class="menu-item"><a href="index.php">Quản lý sản phẩm</a></li>
                    <li class="menu-item"><a href="about.php">Giới thiệu</a></li>
                    <li class="menu-item"><a href="#">Dịch vụ</a></li>
                    <li class="menu-item"><a href="#">Dự án</a></li>
                    <li class="menu-item"><a href="create.php">Sản phẩm</a></li>
                    <li class="menu-item"><a href="contact.php">Liên hệ</a></li>
                    <li class="menu-item"><a href="cart.php"><i class="fas fa-shopping-cart"></i> Giỏ hàng</a></li>
                    <li class="menu-item">
                        <a href="#"><i class="fas fa-user"></i> <?php echo isset($_SESSION['user']) ? $_SESSION['user']['username'] : 'Đăng nhập'; ?></a>
                        <ul class="sub-menu">
                            <?php if (isset($_SESSION['user'])) : ?>
                                <li><a href="create.php">Thêm sản phẩm</a></li>
                                <li><a href="index.php">Quản lý sản phẩm</a></li>
                                <li><a href="register.php">Đăng xuất</a></li>
                            <?php else : ?>
                                <li><a href="login.php">Đăng nhập</a></li>
                                <li><a href="register.php">Đăng ký</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    
    </header>

    <div class="container">
        <div class="contact-form">
            <h2>Liên Hệ Với Chúng Tôi</h2>
            <?php if (isset($_SESSION['error_message'])) : ?>
                <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['message'])) : ?>
                <p class="success-message"><?php echo $_SESSION['message']; ?></p>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Họ và tên:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Tin nhắn:</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Gửi Tin Nhắn</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
