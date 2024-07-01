<?php
session_start(); // Nếu session chưa được bắt đầu trong các tập tin khác
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Về Chúng Tôi - Shop Trái Cây</title>
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
        <h2 class="text-center">Về Chúng Tôi - Shop Trái Cây</h2>
        <div class="about-content">
            <p>Shop trái cây chúng tôi chuyên cung cấp các loại trái cây tươi ngon, sạch đến từ các vùng trồng trái cây uy tín nhất Việt Nam.</p>
            <p>Chúng tôi cam kết mang đến cho khách hàng những sản phẩm chất lượng nhất, được thu hoạch từ những vườn trái cây đạt tiêu chuẩn, đảm bảo an toàn thực phẩm.</p>
            <p>Bên cạnh đó, shop trái cây còn cung cấp dịch vụ giao hàng nhanh chóng và dịch vụ chăm sóc khách hàng tận tình, giúp quý khách có được trải nghiệm mua sắm trực tuyến tuyệt vời nhất.</p>
            <p>Hãy ghé thăm chúng tôi tại cửa hàng để khám phá thêm về các sản phẩm trái cây đa dạng và phong phú của chúng tôi!</p>
            <p>Liên hệ với chúng tôi: Địa chỉ - Số điện thoại - Email</p>
        </div>
    </div>

</body>

</html>
