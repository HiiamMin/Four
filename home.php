<?php
ini_set('display_errors', '1');
include "./DBUtils.php";

session_start();
$dbHelper = new DBUtils();
$products = $dbHelper->select("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Trang Chủ - Mua Hàng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

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
                                <li><a href="login.php">Đăng xuất</a></li>
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
        <h2 class="text-center">Danh Sách Sản Phẩm</h2>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4">
                    <div class="thumbnail">
                        <img src="<?= htmlspecialchars($product['img']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                        <div class="caption">
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <p>Giá: <?= htmlspecialchars($product['price']); ?> VNĐ</p>
                            <p>Giảm giá: <?= htmlspecialchars($product['sale']); ?>VNĐ</p>
                            <p>Trạng thái: <?= htmlspecialchars($product['status']); ?></p>
                            <form action="cart.php" method="get">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']); ?>">
                                <button type="submit" class="btn btn-primary">Mua ngay</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>