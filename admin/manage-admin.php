
<?php include('partials/menu.php'); ?>

        <!-- Main Content Section Starts -->
        <div class="main-content">
            <div class="wrapper">
                <h1>Manage Admin</h1>

                <br />

                <?php
                    if(isset($_SESSION['add']))
                    {
                        echo $_SESSION['add'];     //Displaying Session Message
                        unset($_SESSION['add']);   //Removing Session Message
                    }

                    if(isset($_SESSION['delete']))
                    {
                        echo $_SESSION['delete'];     //Displaying Session Message
                        unset($_SESSION['delete']);   //Removing Session Message
                    }
                    if(isset($_SESSION['update']))
                    {
                        echo $_SESSION['update'];     //Displaying Session Message
                        unset($_SESSION['update']);   //Removing Session Message
                    }
                    if(isset($_SESSION['user-not-found']))
                    {
                        echo $_SESSION['user-not-found'];     //Displaying Session Message
                        unset($_SESSION['user-not-found']);   //Removing Session Message
                    }
                    if(isset($_SESSION['pwd-not-match']))
                    {
                        echo $_SESSION['pwd-not-match'];     //Displaying Session Message
                        unset($_SESSION['pwd-not-match']);   //Removing Session Message
                    }
                    if(isset($_SESSION['change-pwd']))
                    {
                        echo $_SESSION['change-pwd'];     //Displaying Session Message
                        unset($_SESSION['change-pwd']);   //Removing Session Message
                    }
                ?>

                <br><br><br>

                    <!-- Button to Add Admin -->
                    <a href="add-admin.php" class="btn-primary">Add Admin</a>

                <br /><br /><br />

                <table class="tbl-full">
                    <tr>
                        <th>S.N.</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                    
                    <?php
                        //Query to Get all Admin
                        $sql = "SELECT * FROM tbl_admin";
                        //Execute the Query
                        $res = mysqli_query($conn, $sql);

                        //CHeck whether the Query is Executed of Not
                        if($res==TRUE)
                        {
                            // Count Rows to CHeck whether we have data in database or not
                            $count = mysqli_num_rows($res); // Function to get all the rows in Database

                            $sn=1; //Create a variable and Assign the value

                            //CHeck the num of rows
                            if($count>0)
                            {
                                //WE Have data in database
                                while($rows=mysqli_fetch_assoc($res))
                                {
                                    //Using While loop to get all the data from database.
                                    //And while loop will run as long as we have data in database

                                    //Get individual Data
                                    $id=$rows['id'];
                                    $full_name=$rows['full_name'];
                                    $username=$rows['username'];

                                    //Display in our Table
                                    ?>

                                        <tr>
                                            <td><?php echo $sn++; ?></td>
                                            <td><?php echo $full_name; ?></td>
                                            <td><?php echo $username; ?></td>
                                            <td>
                                                <a href="<?php echo SITEURL; ?>admin/update-password.php?id=<?php echo $id; ?>" class="btn-primary">Change Password</a>
                                                <a href="<?php echo SITEURL; ?>admin/update-admin.php?id=<?php echo $id; ?>" class="btn-secondary">Update Admin</a>
                                                <a href="<?php echo SITEURL; ?>admin/delete-admin.php?id=<?php echo $id; ?>" class="btn-danger">Delete Admin</a>
                                            </td>
                                        </tr>


                                    <?php

                                }
                            }
                            else
                            {
                                //We Do not Have Data in Database
                            }
                        }
                    ?>

                </table>

                <div class="clearfix"></div>

            </div>
        </div>
        <!-- Main Content Section End -->

<?php include('partials/footer.php'); ?>