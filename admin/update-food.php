<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <?php
        // Kiểm tra kết nối
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check whether the id is set or not
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
            $res2 = mysqli_query($conn, $sql2);

            if (mysqli_num_rows($res2) > 0) {
                $row2 = mysqli_fetch_assoc($res2);
                $title = $row2['title'];
                $description = $row2['description'];
                $price = $row2['price'];
                $current_image = $row2['image_name'];
                $current_category = $row2['category_id'];
                $featured = $row2['featured'];
                $active = $row2['active'];
            } else {
                $_SESSION['error'] = "<div class='error'>Food not found.</div>";
                header('location:'.SITEURL.'admin/manage-food.php');
                exit();
            }
        } else {
            header('location:'.SITEURL.'admin/manage-food.php');
            exit();
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td><input type="text" name="title" value="<?php echo $title; ?>"></td>
                </tr>
                <tr>
                    <td>Description: </td>
                    <td><textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea></td>
                </tr>
                <tr>
                    <td>Price: </td>
                    <td><input type="number" name="price" value="<?php echo $price; ?>"></td>
                </tr>
                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php
                        if ($current_image != "") {
                            echo "<img src='".SITEURL."images/food/".$current_image."' width='150px'>";
                        } else {
                            echo "<div class='error'>Image Not Added.</div>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category">
                            <?php
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            $res = mysqli_query($conn, $sql);
                            $count = mysqli_num_rows($res);

                            if ($count > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $category_id = $row['id'];
                                    $category_title = $row['title'];
                                    ?>
                                    <option <?php if ($current_category == $category_id) {echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                    <?php
                                }
                            } else {
                                echo "<option value='0'>Category Not Available.</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>New Image: </td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if ($featured == "Yes") {echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if ($featured == "No") {echo "checked";} ?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>
                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if ($active == "Yes") {echo "checked";} ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if ($active == "No") {echo "checked";} ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $current_image = $_POST['current_image'];
            $category = $_POST['category'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            // Xử lý upload ảnh mới
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $image_name = $_FILES['image']['name'];
                $ext = end(explode('.', $image_name));
                $image_name = "Food_Name_".rand(000, 999).'.'.$ext;
                $source_path = $_FILES['image']['tmp_name'];
                $destination_path = "../images/food/".$image_name;

                if (!is_dir("../images/food/")) {
                    $_SESSION['upload'] = "<div class='error'>Image directory does not exist.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                    exit();
                }

                $upload = move_uploaded_file($source_path, $destination_path);
                if ($upload == false) {
                    $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                    exit();
                }

                // Xóa ảnh cũ
                if ($current_image != "" && file_exists("../images/food/".$current_image)) {
                    $remove_path = "../images/food/".$current_image;
                    $remove = unlink($remove_path);
                    if ($remove == false) {
                        $_SESSION['remove-failed'] = "<div class='error'>Failed to remove current Image.</div>";
                        header('location:'.SITEURL.'admin/manage-food.php');
                        exit();
                    }
                }
            } else {
                $image_name = $current_image;
            }

            // Cập nhật cơ sở dữ liệu với prepared statement
            $sql3 = "UPDATE tbl_food SET
                title = ?,
                description = ?,
                price = ?,
                image_name = ?,
                category_id = ?,
                featured = ?,
                active = ?
                WHERE id = ?";

            $stmt = mysqli_prepare($conn, $sql3);
            mysqli_stmt_bind_param($stmt, "ssdssssi", $title, $description, $price, $image_name, $category, $featured, $active, $id);
            $res3 = mysqli_stmt_execute($stmt);

            if ($res3) {
                $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
                header('location:'.SITEURL.'admin/manage-food.php');
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to Update Food: " . mysqli_error($conn) . "</div>";
                header('location:'.SITEURL.'admin/manage-food.php');
            }
            mysqli_stmt_close($stmt);
        }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>