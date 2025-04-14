<?php

    //Include Constants File
    include('../config/constants.php');

    //echo "Delete Page";
    //Check whether the id and image_name value is set or not
    if(isset($_GET['id']) && isset($_GET['image_name']))
    {
        //Get the Value and Delete
        //echo "Get Value and Delete";
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        //Remove the physical image file is available
        if($image_name!="")
        {
            //Image is Available. So remove it
            $path = "../images/food/".$image_name;
            //REmove the Image
            $remove = unlink($path);

            //IF failed to remove image then add an error message and stop the process
            if($remove == false)
            {
                //Set the SEssion Message
                $_SESSION['upload'] = "<div class='error'>Failed to Remove Image File.</div>";
                //REdirect to Manage Category page
                header('location:'.SITEURL.'admin/manage-food.php');
                //Stop the Process
                die();
            }
        }   

        //Delete Data from Database
        //SQL Query to Delete Data from Database
        $sql = "DELETE FROM tbl_food WHERE id=$id";

        //Execute the Query
        $res = mysqli_query($conn, $sql);

        //Check whether the data is delete from database or not
        if($res==true)
        {
            //Food deleted
            $_SESSION['delete'] = "<div class='success'>FOOD Deleted Successfully.</div>";
            //Redirect to Manage Category   
            header('location:'.SITEURL.'admin/manage-food.php');
        }
        else
        {
            //Failed to delete food 
            $_SESSION['delete'] = "<div class='error'>Failed to Delete FOOD.</div>";
            //Redirect to Manage Category
            header('location:'.SITEURL.'admin/manage-food.php');
        }

    }
    else
    {
        //redirect to Manage FOOD Page
        $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access.</div>";    
        header('location:'.SITEURL.'admin/manage-food.php');
    }

?>