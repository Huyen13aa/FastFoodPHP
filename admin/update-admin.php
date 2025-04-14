<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Admin</h1>
        <br><br>

        <?php
        // 1. Lấy ID của Admin cần cập nhật
        $id = $_GET['id'];

        // 2. Tạo truy vấn SQL để lấy thông tin chi tiết của admin theo ID
        $sql = "SELECT * FROM tbl_admin WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id); // "i" là kiểu integer
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        // Kiểm tra xem truy vấn có thực hiện thành công không
        if ($res) {
            $count = mysqli_num_rows($res);
            if ($count == 1) {
                // Lấy dữ liệu của admin
                $row = mysqli_fetch_assoc($res);
                $full_name = $row['full_name'];
                $username = $row['username'];
            } else {
                // Nếu không tìm thấy admin, chuyển hướng về trang quản lý
                header('location: ' . SITEURL . 'admin/manage-admin.php');
                exit();
            }
        } else {
            // Lỗi truy vấn, chuyển hướng về trang quản lý
            header('location: ' . SITEURL . 'admin/manage-admin.php');
            exit();
        }
        mysqli_stmt_close($stmt);
        ?>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="full_name" value="<?php echo $full_name; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" value="<?php echo $username; ?>">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <br><br>
    </div>
</div>

<?php
// Kiểm tra nút Submit được nhấn hay chưa
if (isset($_POST['submit'])) {
    // Lấy dữ liệu từ form
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];

    // Tạo truy vấn SQL để cập nhật admin
    $sql = "UPDATE tbl_admin SET full_name = ?, username = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $full_name, $username, $id); // "ssi" là string, string, integer

    // Thực thi truy vấn
    $res = mysqli_stmt_execute($stmt);

    // Kiểm tra kết quả
    if ($res) {
        $_SESSION['update'] = "<div class='success'>Cập nhật Admin thành công.</div>";
        header('location: ' . SITEURL . 'admin/manage-admin.php');
    } else {
        $_SESSION['update'] = "<div class='error'>Cập nhật Admin thất bại.</div>";
        header('location: ' . SITEURL . 'admin/manage-admin.php');
    }
    mysqli_stmt_close($stmt);
}
?>

<?php include('partials/footer.php'); ?>