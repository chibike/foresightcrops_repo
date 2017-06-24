<?php
    $connect_err_msg = 'Could not connect';
    $servername = "mysql.hostinger.co.uk";
    $database = "u365987764_data2";
    $username = "u365987764_user2";
    $password = "password";
     
    // Create connection
    $db_connect_link= mysqli_connect($servername, $username, $password, $database);
     
    function start_connection()
    {
        // Check connection
        if (!$db_connect_link)
        { 
            die("Connection failed: " . mysqli_connect_error()); 
            return false;
        }
        
        return true;
    }
    
    function verify_user($email, $password)
    {
        global $db_connect_link;
        
        $query = "SELECT id FROM user_details WHERE email=\"$email\" && password=\"$password\"";
        $user_id = -1;
        
        if ( $query_run = mysqli_query($db_connect_link, $query) )
        {
            while ( $query_row = mysqli_fetch_assoc($query_run) )
            {
                $user_id = $query_row['id'];
            }
        }
        else
        {
            echo mysqli_error($db_connect_link);
        }
        
        return $user_id;
    }
    
    function verify_userid($user_id)
    {
        global $db_connect_link;
        
        $query = "SELECT `id` FROM `user_details` WHERE `id`=\"$user_id\"";
        $user_id = -1;
        
        if ($query_run = mysqli_query($db_connect_link, $query) )
        {
            while ( $query_row = mysqli_fetch_assoc($query_run) )
            {
                $user_id = $query_row['id'];
            }
            return $user_id;
        }
        else
        {
            echo mysqli_error($db_connect_link);
        }
        
        return $user_id;
    }
    
    function validate_name($firstname, $lastname)
    {
        $query = "SELECT id FROM user_details WHERE firstname=\"$firstname\" && lastname=\"$lastname\"";
        $user_id = -1;
        
        if ($query_run = mysqli_query($db_connect_link, $query) )
        {
            while ( $query_row = mysqli_fetch_assoc($query_run) )
            {
                $user_id = $query_row['id'];
            }
        }
        else
        {
            echo mysqli_error($db_connect_link);
        }
        
        return $user_id;
    }
    
    function get_user_id($email)
    {
        global $db_connect_link;
        
        $query = "SELECT id FROM user_details WHERE email=\"$email\"";
        $user_id = -1;
        
        if ($query_run = mysqli_query($db_connect_link, $query) )
        {
            while ( $query_row = mysqli_fetch_assoc($query_run) )
            {
                $user_id = $query_row['id'];
            }
        }
        else
        {
            echo mysqli_error($db_connect_link);
        }
        
        return $user_id;
    }
    
    function validate_email($email)
    {
        global $db_connect_link;
        
        $query = "SELECT id FROM user_details WHERE email=\"$email\"";
        $user_id = -1;
        
        if ($query_run = mysqli_query($db_connect_link, $query) )
        {
            while ( $query_row = mysqli_fetch_assoc($query_run) )
            {
                $user_id = $query_row['id'];
            }
        }
        else
        {
            echo mysqli_error($db_connect_link);
        }
        
        return $user_id;
    }
    
    function validate_password($password)
    {
        //return false if password is invalid or malicious;
        return true;
    }
    
    function delete_user($user_id)
    {
        global $db_connect_link;
        
        $query = "DELETE FROM `user_details` WHERE `user_details`.`id` = $user_id";
        if ( $query_run = mysqli_query($db_connect_link, $query) )
        {
            $query = "DELETE FROM `user_address` WHERE `user_address`.`user_id` = $user_id";
            if ( $query_run = mysqli_query($db_connect_link, $query) )
            {
                return true;
            }
            else
            {
                echo mysqli_error($db_connect_link);
            }
        }
        else
        {
            echo mysqli_error($db_connect_link);
        }
    }
    
    function add_user_to_database($firstname, $lastname, $email, $password, $city, $state, $country)
    {
        global $db_connect_link;
        
        $query = "INSERT INTO `user_details` (`id`, `firstname`, `lastname`, `email`, `password`) VALUES (NULL, '$firstname', '$lastname', '$email', '$password')";
        $user_id = -1;
        
        if ( $query_run = mysqli_query($db_connect_link, $query) )
        {
            $user_id = get_user_id($email);
            
            if ($user_id > 0)
            {
                $query = "INSERT INTO `user_address` (`id`, `user_id`, `city`, `state`, `country`) VALUES (NULL, '$user_id', '$city', '$state', '$country')";
                if ( $query_run = mysqli_query($db_connect_link, $query) )
                {
                    return true;
                }
                else
                {
                    echo mysqli_error($db_connect_link);
                }
            }
        }
        else
        {
            echo mysqli_error($db_connect_link);
        }
        
        return false;
    }
    
    function end_connection()
    {
        global $db_connect_link;
        //echo "Connected successfully";
        mysqli_close($db_connect_link);
    }
?>