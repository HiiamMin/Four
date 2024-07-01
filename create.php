<?php
session_start(); // Initialize session

ini_set('display_errors', '1');
include "./DBUtils.php";

$dbHelper = new DBUtils();
$errors = [];
$product = null;
include_once 'connection.php'; // Kết nối đến cơ sở dữ liệu

$error_message = "";

// Kiểm tra nếu người dùng đã đăng nhập và có quyền là admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // Nếu không phải admin, hiển thị thông báo lỗi
    $error_message = "Bạn không có quyền truy cập vào trang này!";
}

// Kiểm tra xem có thông tin sản phẩm được truyền từ trang quản lý sản phẩm không
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = $dbHelper->select("SELECT * FROM products WHERE id = ?", [$id]);
    // Nếu không tìm thấy sản phẩm, điều hướng trở lại trang quản lý sản phẩm
    if (!$product) {
        $_SESSION['message'] = "Không tìm thấy sản phẩm.";
        header("Location: index.php");
        exit();
    }
    // Lấy thông tin sản phẩm từ kết quả truy vấn
    $product = $product[0];
    // Gán thông tin sản phẩm cho các biến hiển thị trên form
    $name = $product['name'];
    $img = $product['img'];
    $price = $product['price'];
    $quantity = $product['quantity'];
    $sale = $product['sale'];
    $status = $product['status'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu khi form được submit
    $name = $_POST['name'];
    $img = $_POST['img'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $sale = $_POST['sale'];
    $status = $_POST['status'];

    if (empty($name)) {
        $errors['name'] = "Vui lòng nhập tên";
    }
    if (empty($img)) {
        $errors['img'] = "Vui lòng gắn link img";
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors['price'] = "Vui lòng nhập giá hợp lệ (số lớn hơn 0)";
    }
    if (!is_numeric($quantity) || $quantity <= 0) {
        $errors['quantity'] = "Vui lòng nhập số lượng hợp lệ (số lớn hơn 0)";
    }
    if (!is_numeric($sale) || $sale <= 0) {
        $errors['sale'] = "Vui lòng nhập giảm giá hợp lệ (số lớn hơn 0)";
    }
    if (empty($status)) {
        $errors['status'] = "Vui lòng nhập trạng thái";
    }

    if (empty($errors)) {
        // Nếu có sản phẩm được chọn từ trang quản lý sản phẩm (chế độ chỉnh sửa)
        if ($product) {
            // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu
            $updated = $dbHelper->update("products", array(
                'name' => $name,
                'img' => $img,
                'price' => $price,
                'quantity' => $quantity,
                'sale' => $sale,
                'status' => $status
            ), "id = $id");

            if ($updated) {
                $_SESSION['message'] = "Thông tin sản phẩm đã được cập nhật thành công.";
                header("Location: index.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Có lỗi xảy ra khi cập nhật sản phẩm.</div>";
            }
        } else { // Nếu không có sản phẩm được chọn (chế độ thêm mới sản phẩm)
            $created = $dbHelper->insert("products", array(
                'name' => $name,
                'img' => $img,
                'price' => $price,
                'quantity' => $quantity,
                'sale' => $sale,
                'status' => $status
            ));

            if ($created) {
                $_SESSION['message'] = "Sản phẩm đã được thêm thành công.";
                header("Location: index.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Sản phẩm đã được thêm thành công.</div>";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/7f10d10fe3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="create.css">
    <title>Form Sản Phẩm</title>
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
            <h2 class="text-center">Thêm Sản Phẩm</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?= htmlspecialchars($name ?? '') ?>">
                    <?php if (!empty($errors['name'])) : ?>
                        <span class="text-danger"><?php echo $errors['name']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="img">Image</label>
                    <input type="text" class="form-control" id="img" name="img" placeholder="Enter image URL" value="<?= htmlspecialchars($img ?? '') ?>">
                    <?php if (!empty($errors['img'])) : ?>
                        <span class="text-danger"><?php echo $errors['img']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" value="<?= htmlspecialchars($price ?? '') ?>">
                    <?php if (!empty($errors['price'])) : ?>
                        <span class="text-danger"><?php echo $errors['price']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" value="<?= htmlspecialchars($quantity ?? '') ?>">
                    <?php if (!empty($errors['quantity'])) : ?>
                        <span class="text-danger"><?php echo $errors['quantity']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="sale">Sale</label>
                    <input type="number" class="form-control" id="sale" name="sale" placeholder="Enter sale" value="<?= htmlspecialchars($sale ?? '') ?>">
                    <?php if (!empty($errors['sale'])) : ?>
                        <span class="text-danger"><?php echo $errors['sale']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" name="status" placeholder="Enter status" value="<?= htmlspecialchars($status ?? '') ?>">
                    <?php if (!empty($errors['status'])) : ?>
                        <span class="text-danger"><?php echo $errors['status']; ?></span>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>