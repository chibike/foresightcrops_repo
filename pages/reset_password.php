<?php
    require "../includes/php/config_script.php";
    require "../includes/php/external_functions_script.php";
    
    $email = $email_error = "";
    $registration_page = constant("PAGES_DIR").'register.php';
    $login_page = "/";
    
    //form is submitted with POST method
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["email"]))
        {
            $email_error = "*This field is required";
        }
        else
        {
            $email = parse_input($_POST["email"]);
            
            if ( !filter_var($email, FILTER_VALIDATE_EMAIL) )
            {
                $email_error = "Invaild email format"; 
            }
        }
        
        if ($email_error == '')
        {
            if ( reset_password($email) )
            {
                //echo "A new password has been sent to your email address";
                $email_error = "A new password has been sent to your email address";
            }
            else
            {
                //echo "This email is not recognized";
                $email_error = "This email is not recognized";
            }
        }
        
        unset($_POST['submit']);
    }
    
    function parse_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Reset Password Page - Foresight Crops</title>
        <link rel="stylesheet" type="text/css" href="<?=constant("CSS_DIR")?>main.css">
        <script src="<?=constant("JS_DIR")?>main.js"></script>
    </head>
    <body>
        <div class="login-page">
            <div class="form">
                <form class="login-form" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
                    <label>Please enter your email</label>
                    <input type="text" placeholder="email" name="email" value="<?php $email; ?>" required autofocus/>
                    <span class="error"><?= $email_error ?></span>
                    
                    <button>Reset</button>
                    
                    <p class="message"><a href="<?= $registration_page; ?>">Create Account</a>
                     or <a href="<?= $login_page; ?>">Sign in</a></p>
                </form>
            </div>
        </div>
    </body>
</html>