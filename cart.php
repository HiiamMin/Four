<?php
session_start();
include "./DBUtils.php";

ini_set('display_errors', '0');
error_reporting(0);

$dbHelper = new DBUtils();
include_once 'connection.php'; // Kết nối đến cơ sở dữ liệu

$error_message = "";

// Kiểm tra nếu người dùng đã đăng nhập và có quyền là admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Nếu không phải admin, hiển thị thông báo lỗi
    $error_message = "Bạn không có quyền truy cập vào trang này!";
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý thêm sản phẩm vào giỏ hàng
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
    if (isset($_SESSION['cart'][$product_id])) {
        // Nếu sản phẩm đã tồn tại, tăng số lượng lên 1
        $_SESSION['cart'][$product_id]++;
    } else {
        // Nếu sản phẩm chưa tồn tại, thêm vào giỏ hàng với số lượng là 1
        $_SESSION['cart'][$product_id] = 1;
    }

    // Điều hướng đến trang giỏ hàng
    header('Location: cart.php');
    exit();
}

// Xử lý tăng số lượng sản phẩm trong giỏ hàng
if (isset($_GET['action']) && $_GET['action'] == 'increase' && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Tăng số lượng sản phẩm lên 1
    $_SESSION['cart'][$product_id]++;

    // Điều hướng trở lại trang giỏ hàng
    header('Location: cart.php');
    exit();
}

// Xử lý giảm số lượng sản phẩm trong giỏ hàng
if (isset($_GET['action']) && $_GET['action'] == 'decrease' && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Giảm số lượng sản phẩm đi 1, đảm bảo không nhỏ hơn 1
    if ($_SESSION['cart'][$product_id] > 1) {
        $_SESSION['cart'][$product_id]--;
    }

    // Điều hướng trở lại trang giỏ hàng
    header('Location: cart.php');
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Xóa sản phẩm khỏi giỏ hàng
    unset($_SESSION['cart'][$product_id]);

    // Điều hướng trở lại trang giỏ hàng
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Giỏ Hàng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/7f10d10fe3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="cart.css">
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
        <h2 class="text-center">Giỏ Hàng</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0; // Khởi tạo tổng tiền
                foreach ($_SESSION['cart'] as $product_id => $quantity) :
                    // Lấy thông tin sản phẩm từ cơ sở dữ liệu dựa trên ID
                    $product = $dbHelper->select("SELECT * FROM products WHERE id = ?", [$product_id]);
                    if (!$product) {
                        continue;
                    }
                    $product = $product[0];
                    $product_total = $product['price'] * $quantity; // Tính tổng tiền cho sản phẩm này
                    $total_price += $product_total; // Cộng vào tổng tiền
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($product['img']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" style="width: 100px;"></td>
                        <td><?= htmlspecialchars($product['name']); ?></td>
                        <td><?= htmlspecialchars($product['price']); ?></td>
                        <td>
                            <!-- Nút tăng số lượng sản phẩm -->
                            <a href="cart.php?action=increase&id=<?= $product_id; ?>" class="btn btn-xs btn-success">+</a>
                            <!-- Hiển thị số lượng -->
                            <?= $quantity; ?>
                            <!-- Nút giảm số lượng sản phẩm -->
                            <a href="cart.php?action=decrease&id=<?= $product_id; ?>" class="btn btn-xs btn-warning">-</a>
                        </td>
                        <td><?= htmlspecialchars($product_total); ?></td>
                        <td><a href="cart.php?action=remove&id=<?= $product_id; ?>" class="btn btn-danger">Xóa</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($_SESSION['cart'])) : ?>
            <p>Giỏ hàng trống.</p>
        <?php endif; ?>
        <p><strong>Tổng tiền:</strong> <?= htmlspecialchars($total_price); ?></p>
        <p><a href="checkout.php" class="btn btn-success">Thanh toán</a></p>
        <p><a href="home.php" class="btn btn-primary">Tiếp tục mua hàng</a></p>
    </div>
</body>

</html>