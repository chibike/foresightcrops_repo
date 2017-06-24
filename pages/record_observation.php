<?php
    require "../includes/php/config_script.php";
    require "../includes/php/login_user.php";
    
    $login_page = "../";
    if ( !current_user_is_logged_in() )
    {
        header('Location: '.$login_page);
    }
    
    $pictures_dir = "../..".constant("PICT_DIR");
    $main_screen = "../..".constant("PAGES_DIR").'main_screen.php';
    
    $data = parse_input( $_POST['comment'] );
    $picture_name = parse_input( $_FILES['picture']['name'] );
    $picture_size = parse_input( $_FILES['picture']['size'] );
    $picture_type = parse_input( $_FILES['picture']['type'] );
    $picture_tmp_name = parse_input( $_FILES['picture']['tmp_name'] );
    $picture_error = $_FILES['picture']['error'];
    $picture_error_msg = '';
    
    $selected_pest = parse_input( $_POST['selected_pests'] );
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['upload_observation']) && isset($_POST['comment']))
        {
            $data = htmlentities($_POST['comment']);
            if (strlen($data) < 0)
            {
            }
            else
            {
                //echo $data."</br>";
            }
        }
        
        if ( isset($picture_name) )
        {
            if ( !empty($picture_name) )
            {
                // echo "pic name = ".$picture_name."</br>";
                // echo "pic size = ".$picture_size."</br>";
                // echo "pic type = ".$picture_type."</br>";
                // echo "pic tmp name = ".$picture_tmp_name."</br>";
                // echo "pic error = ".$picture_error."</br>";
                
                savePicture($picture_name, $pictures_dir, $picture_type, $picture_size, $picture_tmp_name);
            }
            else
            {
                $picture_error_msg = "This field is required";
            }
        }
    }
    
    function savePicture($pic_name, $pic_dir, $pic_type, $pic_size, $pic)
    {
        global $db_connect_link;
        
        if ( !current_user_is_logged_in() )
        {
            return false;
        }
        
        $time_data = time();
        $pic_extention = getFileExtention( $pic_name );
        $new_pic_name = "$time_data.$pic_extention";
        
        if ( validatePicture($pic_name, $pic_type, $pic_size, $pic_extention) )
        {
            $new_pic_location = $pic_dir.$new_pic_name;
            if( move_uploaded_file($pic, $new_pic_location) )
            {
                $user_id = $_SESSION['userid'];
                $query = "INSERT INTO `user_pictures` (`id`, `user_id`, `upload_time`, `picture_location`) VALUES (NULL, \"$user_id\", CURRENT_TIMESTAMP, \"$new_pic_location\")";
                
                if( $query_run = mysqli_query($db_connect_link, $query) )
                {
                    return true;
                }
                else
                {
                    echo mysqli_error($db_connect_link);
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    function validatePicture($pic_name, $pic_type, $pic_size, $pic_extention)
    {
        global $picture_error_msg;
        
        $max_file_size = 300000000;
        $file_types = array("image/jpeg");
        $file_extentions = array("jpeg", "jpg","png");
        
        $file_types_length = count($file_types); $file_type_valid = false;
        for ( $x=0; $x<$file_types_length; $x++ )
        {
            $file_type = $file_types[$x];
            if ( $pic_type == $file_type )
            {
                $file_type_valid = true; break;
            }
        }
        
        $file_extentions_length = count($file_extentions); $file_extention_valid = false;
        for ( $x=0; $x<$file_extentions_length; $x++ )
        {
            $file_extention = $file_extentions[$x];
            if ( $pic_extention == $file_extention )
            {
                $file_extention_valid = true; break;
            }
        }
        
        if ( !$file_extention_valid || !$file_type_valid )
        {
            $picture_error_msg = "Invalid file type"; return false;
        }
        
        if ( $pic_size > $max_file_size )
        {
            $picture_error_msg = "Invalid file size (Image must be less than 2mb)"; return false;
        }
        
        if ( empty($picture_error_msg) )
        {
            $picture_error_msg = "Upload successful";
        }
        
        return true;
    }
    
    function getFileExtention( $filename )
    {
        return strtolower( substr( strtolower($filename), strpos($filename, ".")+1 ) );
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Record Observation - Foresight Crops</title>
        <link rel="stylesheet" type="text/css" href="<?=constant("CSS_DIR")?>record_observation.css">
    </head>
    
    <body>
        <div class="record-observation-page">
            <div class="form">
                <div>
                    <button>Record My Location</button>
                </div>
                <form class="record-observation-form" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
                    <div>
                        <span class="error"><?= $picture_error_msg ?></span>
                        <input type="file" name="picture" class="picture-button" value="Click to select picture" required />
                    </div>
                    
                    <input type="text" placeholder="pests involved" name="selected_pests" value="<?php echo $selected_pest; ?>" list="pests_list" required autofocus />
                    <datalist id="pests_list">
                        <option value="Ants">
                        <option value="Bees">
                        <option value="Centipedes">
                        <option value="Drugstore beetles">
                        <option value="Earthworms">
                    </datalist>
                    
                    <label>Comments?</label>
                    <textarea class="comments" name="comment"><?php echo "Please leave your comments here&#13;&#10;Thank you."?></textarea>
                    
                    <button name="upload_observation">Upload My Observation</button>
                    <p class="message">No observations? <a href="main_screen.php">Go Home</a></p>
                </form>
            </div>
        </div>
    </body>
</html>