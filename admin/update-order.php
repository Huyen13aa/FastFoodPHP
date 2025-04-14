<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Order</h1>

        <br><br>

        <?php
        // 1. Lấy ID của đơn hàng cần cập nhật
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('location: ' . SITEURL . 'admin/manage-order.php');
            exit();
        }
        $id = $_GET['id'];

        // 2. Tạo truy vấn SQL để lấy thông tin chi tiết của đơn hàng theo ID
        $sql = "SELECT * FROM tbl_order WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        // Kiểm tra xem truy vấn có thực hiện thành công không
        if ($res && mysqli_num_rows($res) == 1) {
            // Lấy dữ liệu của đơn hàng
            $row = mysqli_fetch_assoc($res);
            $food = $row['food'];
            $price = $row['price'];
            $qty = $row['qty'];
            $status = $row['status'];
            $customer_name = $row['customer_name'];
            $customer_contact = $row['customer_contact'];
            $customer_email = $row['customer_email'];
            $customer_address = $row['customer_address'];
        } else {
            // Nếu không tìm thấy đơn hàng, chuyển hướng về trang quản lý
            $_SESSION['error'] = "<div class='error'>Không tìm thấy đơn hàng.</div>";
            header('location: ' . SITEURL . 'admin/manage-order.php');
            exit();
        }
        mysqli_stmt_close($stmt);
        ?>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Food Name: </td>
                    <td>
                        <b><?php echo htmlspecialchars($food); ?></b>
                    </td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><b><?php echo htmlspecialchars($price); ?></b></td>
                </tr>
                <tr>
                    <td>Qty:</td>
                    <td>
                        <input type="number" name="qty" value="<?php echo htmlspecialchars($qty); ?>" min="1" required>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <select name="status">
                            <option value="Ordered" <?php if ($status == "Ordered") echo "selected"; ?>>Ordered</option>
                            <option value="On Delivery" <?php if ($status == "On Delivery") echo "selected"; ?>>On Delivery</option>
                            <option value="Delivered" <?php if ($status == "Delivered") echo "selected"; ?>>Delivered</option>
                            <option value="Cancelled" <?php if ($status == "Cancelled") echo "selected"; ?>>Cancelled</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Customer Name: </td>
                    <td>
                        <input type="text" name="customer_name" value="<?php echo htmlspecialchars($customer_name); ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Customer Contact: </td>
                    <td>
                        <input type="text" name="customer_contact" value="<?php echo htmlspecialchars($customer_contact); ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Customer Email: </td>
                    <td>
                        <input type="email" name="customer_email" value="<?php echo htmlspecialchars($customer_email); ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Customer Address: </td>
                    <td>
                        <textarea name="customer_address" cols="30" rows="5" required><?php echo htmlspecialchars($customer_address); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>">
                        <input type="submit" name="submit" value="Update Order" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
        // Xử lý khi form được gửi
        if (isset($_POST['submit'])) {
            // Lấy dữ liệu từ form
            $id = $_POST['id'];
            $price = floatval($_POST['price']);
            $qty = isset($_POST['qty']) && is_numeric($_POST['qty']) ? intval($_POST['qty']) : $qty;
            $total = $price * $qty;

            // Kiểm tra và gán giá trị, giữ nguyên dữ liệu cũ nếu không hợp lệ
            $status = in_array($_POST['status'], ['Ordered', 'On Delivery', 'Delivered', 'Cancelled']) ? $_POST['status'] : $status;
            $customer_name = !empty(trim($_POST['customer_name'])) ? trim($_POST['customer_name']) : $customer_name;
            $customer_contact = !empty(trim($_POST['customer_contact'])) ? trim($_POST['customer_contact']) : $customer_contact;
            $customer_email = !empty(trim($_POST['customer_email'])) ? trim($_POST['customer_email']) : $customer_email;
            $customer_address = !empty(trim($_POST['customer_address'])) ? trim($_POST['customer_address']) : $customer_address;

            // Câu lệnh chuẩn bị để cập nhật dữ liệu
            $sql2 = "UPDATE tbl_order SET 
                     qty = ?, 
                     total = ?, 
                     status = ?, 
                     customer_name = ?, 
                     customer_contact = ?, 
                     customer_email = ?, 
                     customer_address = ? 
                     WHERE id = ?";
            
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "idsssssi", $qty, $total, $status, $customer_name, $customer_contact, $customer_email, $customer_address, $id);
            
            // Thực thi câu lệnh
            if (mysqli_stmt_execute($stmt2)) {
                // Cập nhật thành công
                $_SESSION['update'] = "<div class='success'>Update Order Successfully.</div>";
                header('location: ' . SITEURL . 'admin/manage-order.php');
                exit();
            } else {
                // Cập nhật thất bại
                $_SESSION['update'] = "<div class='error'>Failed to Update Order.</div>";
                header('location: ' . SITEURL . 'admin/manage-order.php');
                exit();
            }
            
            mysqli_stmt_close($stmt2);
        }
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>