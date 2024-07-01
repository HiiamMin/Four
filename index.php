<?php
ini_set('display_errors', '1');
include "./DBUtils.php";

session_start();
$dbHelper = new DBUtils();
$products = $dbHelper->select("SELECT * FROM products");
include_once 'connection.php'; // Kết nối đến cơ sở dữ liệu

$error_message = "";

// Kiểm tra nếu người dùng đã đăng nhập và có quyền là admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Nếu không phải admin, hiển thị thông báo lỗi
    $error_message = "Bạn không có quyền truy cập vào trang này!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'delete': {
                $id = $_POST['id'];
                // Xóa chỉ một sản phẩm từ cơ sở dữ liệu
                $result = $dbHelper->delete('products', 'id=' . $id);

                $_SESSION['message'] = "Sản phẩm đã được xóa thành công.";
                header("Location: index.php");
                exit();
            }
            break;
        case 'edit': {
                $id = $_POST['id'];
                // Điều hướng đến trang create.php với thông tin sản phẩm cần chỉnh sửa
                header("Location: create.php?id=$id");
                exit();
            }
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Quản Lý Sản Phẩm</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
        <?php if (!empty($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php else : ?>
            <h2 class="text-center">Quản Lý Sản Phẩm</h2>
            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>img</th>
                        <th>price</th>
                        <th>quantity</th>
                        <th>sale</th>
                        <th>status</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $row) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><img src="<?= htmlspecialchars($row['img']); ?>" alt="<?= htmlspecialchars($row['name']); ?>" style="width: 100px;"></td>
                                <td><?= htmlspecialchars($row['price']); ?> VNĐ</td>
                                <td><?= htmlspecialchars($row['quantity']); ?></td>
                                <td><?= htmlspecialchars($row['sale']); ?></td>
                                <td><?= htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']); ?>">
                                        <button class="btn btn-danger" type="submit" name="action" value="delete">Xóa</button>
                                        <button class="btn btn-primary" type="submit" name="action" value="edit">Sửa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center">Không có sản phẩm nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <form method="GET" action="create.php">
                <button class="btn btn-success" type="submit">Thêm sản phẩm</button>
                <a href="home.php" class="btn btn-primary">Xem Sản Phẩm</a>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>