<?php include('../config/constants.php');  ?>

<html>
    <head>
        <title>Login - Food Order System</title>
        <link rel="stylesheet" href="../css/admin.css">
    </head>

    <body>
        
        <div class="login">
            <h1 class="text-center">Login</h1>
            <br><br>

            <?php

                if(isset($_SESSION['login']))
                {
                    echo $_SESSION['login'];     //Displaying Session Message
                    unset($_SESSION['login']);   //Removing Session Message
                }
                if(isset($_SESSION['no-login-message']))
                {
                    echo $_SESSION['no-login-message'];     //Displaying Session Message
                    unset($_SESSION['no-login-message']);   //Removing Session Message
                }
            
            ?>
            <br><br>


            <!-- Login form start here -->
             <form action="" method="POST" class="text-center">
                Username: <br>
                <input type="text" name="username" placeholder="Enter Your Username"> <br><br>

                Password: <br>
                <input type="password" name="password" placeholder="Enter Password"> <br><br>

                <input type="submit" name="submit" value="login" class="btn-primary"> 
                <br><br>
             </form>
            <!-- Login form start here -->

        </div>

    </body>
</html>

<?php
    //CHeck whether the Submit Button is Clicked on Not
    if(isset($_POST['submit']))
    {
        //Process for login
        //1. Get the Data from Login form
        //$username = $_POST['username'];
        $username =  mysqli_real_escape_string($conn, $_POST['username']);
        
        $raw_password = md5($_POST['password']);

        $password = mysqli_real_escape_string($conn, $raw_password);

        //2. SQL to check whether the user with username and password exists or not
        $sql = "SELECT * FROM tbl_admin WHERE username = '$username' AND password = '$password'";

        //3. Executed the Query
        $res = mysqli_query($conn, $sql);

        //4. Count rows to check whether the user exists or not
        $count = mysqli_num_rows($res);

        if($count == 1)
        {
            //User Available and Login Successfully
            $_SESSION['login'] = "<div class='success'>Login Successfully</div>";
            $_SESSION['user'] = $username; //To check whether the user is logged in or not and loggout will unset itss
            //Redirect to home Page/Dashboard
            header('location:'.SITEURL.'admin/');
        }
        else
        {
            //User not Available and Login Failed
            $_SESSION['login']="<div class='error text-center'>Username or Password not match</div>";
            //Redirect to home Page/Dashboard
            header('location:'.SITEURL.'admin/login.php');
        }
    }
?>