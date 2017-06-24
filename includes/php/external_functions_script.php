<?php
    require "sql_interface.php";
    
    function reset_password($email)
    {
        global $db_connect_link;
        
        $user_id = user_exists($email);
        if ( $user_id < 0 )
        {
            return false;
        }

        $to = "$email";
        $subject = "An Update to Your Account";
        $new_password = generate_password();
        
        $message = "<html>
    <head>
    <title>An Update to Your Account</title>
    </head>
    <body>
        <p>
            Hi, \r\n\r\n
        </p>
        
        <p>
            Please be informed that there has been an update to your account.\r\n
        <p>
        </p>
            Your New Password is <strong><code><u>";
        $message .= $new_password;
        $message .= "</u></code></strong>.\r\n
        </p>
        
        <p>
            Please use this password to log into your account.\r\n
        </p>
        <p>
            link: <a href=\"http://foresightcrops.xyz\">http://foresightcrops.xyz</a>\r\n\r\n
        </p>
        
        <p>
            Regards,\r\n
        </p>
        <p>
            Foresight Crops.
        </p>
        <p>
            This is an automated mail, please do not reply.\r\n
            Copyright(c) 2017: All rights reserved.
        </p>
    </body>
</html>";
        
        $new_password = md5($new_password);
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: donotreply@foresightcorps.xyz" . "\r\n";
        //$headers .= 'Cc: myboss@example.com'."\r\n";
        
        $query = "UPDATE `user_details` SET `password` = \"$new_password\" WHERE `user_details`.`id` = $user_id";
        if( $query_run = mysqli_query($db_connect_link, $query) )
        {
            if ( send_mail($to, $subject, $message, $headers) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            echo "</br>Err: reset_password -> ";
            echo mysqli_error($db_connect_link);
            return false;
        }
        return true;
    }
    
    function user_exists($email)
    {
        global $db_connect_link;
        
        $user_id = -1;
        $query = "SELECT id FROM user_details WHERE email=\"$email\"";
        
        if( $query_run = mysqli_query($db_connect_link, $query) )
        {
            while ( $query_row = mysqli_fetch_assoc($query_run) )
            {
                $user_id = $query_row['id'];
            }
            return $user_id;
        }
        else
        {
            echo "</br>Err: user_exists -> ";
            echo mysqli_error($db_connect_link);
            return $user_id;
        }
        return $user_id;
    }
    
    function generate_password()
    {
        return time();
    }
    
    function send_mail($to, $subject, $message, $headers)
    {
        //$message = wordwrap($message);
        return mail($to, $subject, $message, $headers);
    }
    
?>