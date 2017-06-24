<?php
    function register_user($firstname, $lastname, $email, $password, $city, $state, $country)
    {
        if ( !validate_email($email) || !validate_name($firstname, $lastname) || !validate_password($password) )
        {
            return false;
        }
        
        return add_user_to_database($firstname, $lastname, $email, $password, $city, $state, $country);
    }
?>