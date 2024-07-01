<?php
session_start();
include "./DBUtils.php";

ini_set('display_errors', '0');
error_reporting(0);

$dbHelper = new DBUtils();
include_once 'connection.php'; // Kết nối đến cơ sở dữ liệu

$error_message = "";

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Xử lý khi người dùng xác nhận thanh toán
if (isset($_POST['checkout'])) {
    
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $total_price = $_POST['total_price'];
    $user_id = $_SESSION['user']['id'];
    try {
    // Lưu thông tin đơn hàng vào cơ sở dữ liệu
    $order_id = $dbHelper->insert("INSERT INTO orders (user_id, name, address, phone, email, total_price) VALUES (?, ?, ?, ?, ?, ?)", [$user_id, $name, $address, $phone, $email, $total_price]);
   // thêm mấy cái dữ liệu vào database
    // Lưu chi tiết đơn hàng
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product = $dbHelper->select("SELECT * FROM products WHERE id = ?", [$product_id]);
            if ($product) {
                $product = $product[0];
                $price = $product['price'];
                $dbHelper->insert("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)", [$order_id, $product_id, $quantity, $price]);
            }
        }
    }
    catch(Exception $e) {
        var_dump($e);
        echo 'Message: ' .$e->getMessage();
        die;
      }
    
    // die;
    // Xóa giỏ hàng sau khi thanh toán
    unset($_SESSION['cart']);

    // Điều hướng đến trang cảm ơn
    header('Location: thank_you.php');
    exit();
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Thanh Toán</title>
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
        <h2 class="text-center">Xác Nhận Thanh Toán</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
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
                        <td><?= $quantity; ?></td>
                        <td><?= htmlspecialchars($product_total); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Tổng tiền:</strong> <?= htmlspecialchars($total_price); ?></p>
        <form action="checkout.php" method="POST">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($total_price); ?>">
            <div class="form-group">
                <label for="name">Tên người nhận:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <p><button type="submit" name="checkout" class="btn btn-success">Xác nhận thanh toán</button></p>
            <p><a href="cart.php" class="btn btn-primary">Quay lại giỏ hàng</a></p>
        </form>
    </div>
</body>

</html>
