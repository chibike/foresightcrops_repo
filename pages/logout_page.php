<?php
    
    require "../includes/php/login_user.php";
    
    $login_page = "../";
    if ( !current_user_is_logged_in() )
    {
        header('Location: '.$login_page);
    }
    
    logout( $_SESSION['username'] );
    
    header('Location: '.$login_page);
    
?>