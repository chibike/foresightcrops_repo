<?php
    require "includes/php/login_user.php";
    require "includes/php/config_script.php";
    
    $password = $email = $email_error = $password_error = "";
    $next_page = constant("PAGES_DIR").'main_screen.php';
    $registration_page = constant("PAGES_DIR").'register.php';
    $reset_password_page = constant("PAGES_DIR").'reset_password.php';
    
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

        if (empty($_POST["password"]))
        {
            $password_error = "Password is required";
        }
        else if ( strlen($_POST["password"]) <= 8 )
        {
            $password_error = "Invaild password";
        }
        else
        {
            $password = md5(parse_input($_POST["password"]));
        }
        
        if ($email_error == '' and $password_error == '')
        {
            if ( login($email, $password) )
            {
                header('Location: '.$next_page);
                die();
            }
            else
            {
                $password_error = "Invaild email or password";
            }
        }
        
        unset($_POST['submit']);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login Page - Foresight Crops</title>
        <link rel="stylesheet" type="text/css" href="<?=constant("CSS_DIR")?>main.css">
        <script src="<?=constant("JS_DIR")?>main.js"></script>
    </head>
    <body>
        <div class="login-page">
            <div class="form">
                <form class="login-form" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
                    <label>Login Details</label>
                    <input type="text" placeholder="email" name="email" value="<?php $email; ?>" required autofocus/>
                    <span class="error"><?= $email_error ?></span>
                    
                    <input type="password" placeholder="password" name="password" required/>
                    <span class="error"><?= $password_error ?></span>
                    
                    <button>login</button>
                    
                    <p class="message"><a href="<?php echo $registration_page; ?>">Create Account</a>
                     or <a href="<?php echo $reset_password_page; ?>">Reset Password</a></p>
                </form>
            </div>
        </div>
    </body>
</html>