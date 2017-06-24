<?php
    session_start();
    
    require "sql_interface.php";
    
    function login($email, $password)
    {
        if ( is_logged_in($email) )
        {
            if ( !logout( $email ) )
            {
                return false;
            }
            else
            {
                return login($email, $password);
            }
        }
        
        $user_id = verify_user($email, $password);
        if($user_id < 0)
        {
            return false;
        }
        
        $_SESSION['userid'] = $user_id;
        $_SESSION['username'] = $email;
        $_SESSION['is_online'] = "true";
        
        return true;
    }
    
    function logout($email)
    {
        if ( !is_logged_in($email) )
        {
            return false;
        }
        
        unset(   $_SESSION['userid']  );
        unset(  $_SESSION['username'] );
        unset( $_SESSION['is_online'] );
        
        return true;
    }
    
    function current_user_is_logged_in()
    {
        if ( isset($_SESSION['is_online']) )
        {
            return $_SESSION['is_online'];
        }
        else
        {
            return false;
        }
        return false;
    }
    
    function is_logged_in($email)
    {
        if ( !isset($_SESSION['is_online']) )
        {
            return false;
        }
        else if ( $_SESSION['is_online'] != "true" )
        {
            return false;
        }
        
        if ( isset($_SESSION['username']) )
        {
            return $_SESSION['username'] == $email;
        }
        
        return false;
    }
    
    function parse_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>