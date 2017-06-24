<?php
    require "php/register_user.php";
    require "php/login_user.php";
    
    $login_page = "index.php";
    $next_page = 'main_screen.php';
    
    $firstname_error = $lastname_error = $email_error = $password_error = '';
    $email = $firstname = $lastname = $password = '';

    $address = $city = $state = $postal_code = $country = '';
    
    $empty_err_msg = "*This field is required";
    $password_do_not_match_err_msg = "Passwords do not match";
    $password_invalid_length_err_msg = "Number of password characters must be >= 8 and <= 14 ";
    
    $email_invalid_format_err_msg = "Invaild email format";
    
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if( empty($_POST["password"]) || empty($_POST["password_again"]) )
        {
            $password_error = $empty_err_msg;
        }
        else if ( parse_input($_POST["password"]) != parse_input($_POST["password_again"]) )
        {
            $password_error = $password_do_not_match_err_msg;
        }
        else if ( strlen(parse_input($_POST["password"])) < 8 || strlen(parse_input($_POST["password"])) > 15)
        {
            $password_error = $password_invalid_length_err_msg;
        }
        else
        {
            $password = md5(parse_input($_POST["password"]));
        }
        
        if(empty($_POST["firstname"]))
        {
            $firstname_error = $empty_err_msg;
        }
        else
        {
            if ( !preg_match( "/^[a-zA-Z ]*$/", parse_input($_POST["firstname"]) ) )
            {
                $firstname_error = "Only letters and white space allowed"; 
            }
            else
            {
                $firstname = parse_input($_POST["firstname"]);
            }
        }
        
        if(empty($_POST["lastname"]))
        {
            $lastname_error = $empty_err_msg;
        }
        else
        {
            if ( !preg_match( "/^[a-zA-Z ]*$/", parse_input($_POST["lastname"]) ) )
            {
                $lastname_error = "Only letters and white space allowed"; 
            }
            else
            {
                $lastname = parse_input($_POST["lastname"]);
            }
        }
        
        if(empty($_POST["email"]))
        {
            $email_error = $empty_err_msg;
        }
        else
        {
            if ( !filter_var(parse_input($_POST["email"]), FILTER_VALIDATE_EMAIL) )
            {
                $email_error = $email_invalid_format_err_msg; 
            }
            else
            {
                $email = parse_input($_POST["email"]);
            }
        }
        
        if( ($password_error == '') && ($firstname_error == '') && ($lastname_error == '') && ($email_error == '') && ($password_error == '') )
        {
            if ( register_user($firstname, $lastname, $email, $password) )
            {
                if (login($email, $password))
                {
                    header('Location: '.$next_page);
                    die();
                }
                else
                {
                    $password_error = 'Sorry could not login';
                }
            }
            else
            {
                $password_error = 'Sorry could not login (user exists)';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registration Beta - Foresight Crops</title>
        <link rel="stylesheet" type="text/css" href="css/register.css">
        <script src="java/register.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAp8GpULr7CJpJ-AQkPpiVCjl2fIU5qszY&libraries=places&callback=initialize"async defer></script>
    </head> 
    
    <body>
        <div class="register-page">
            <div class="form">
                <form class="register-form" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
                    <div class="information-form">
                        <label class="weak">Currently in Beta Mode </label>
                        <label><strong>Please NO! sensitve information</strong><br> </label>
                        <label>Infomation</label>
                        <input name="firstname" type="text" placeholder="firstname" value="<?php echo $firstname; ?>"  autofocus/>
                        <span class="error"><?= $firstname_error ?></span>
                        
                        <input name="lastname" type="text" placeholder="lastname" value="<?php echo $lastname; ?>" />
                        <span class="error"><?= $lastname_error ?></span>
                        
                        <input name="email" type="text" placeholder="email address" value="<?php echo $email; ?>" />
                        <span class="error"><?= $email_error ?></span>
                        
                        <input name="password" type="password" placeholder="password" />
                        <input name="password_again" type="password" placeholder="password again" />
                        <span class="error"><?= $password_error ?></span>
                    </div>
                  
                    <div class="location-form" id="address">
                        <label>Address</label>
                        <!-- Search Field -->
                        <div class="search-field" id="locationField">
                            <input name="address" class="address-field" id="autocomplete" placeholder="address or post_code" onFocus="geolocate()" type="text"></input>
                        </div>
                        
                        <div class="disabled">
		                    <!-- City -->
		                    <div class="city-field">
		                        <input name="city" type="text" placeholder="city" id="locality" onkeypress="return false;" />
		                    </div>

		                    <!-- State & Zip -->
		                    <div class="state-field">
		                        <input name="state" type="text" placeholder="state" id="administrative_area_level_1" onkeypress="return false;" />
		                    </div>
		                    
		                    <!-- Postal Code -->
		                    <div class="postal-code-field">
		                        <input name="postal_code" type="text" placeholder="postal code" id="postal_code" onkeypress="return false;" />
		                    </div>
		                    
		                    <!-- Country -->
		                    <div class="country-field">
		                        <input name="country" type="text" placeholder="country" id="country" onkeypress="return false;" />
		                    </div>
		                </div>
                    </div>
                  
                    <div class="pests-crops-form">
                        <label>Crops and Common Pests</label>
                        <input type="text" placeholder="crops"/>
                        <input type="text" placeholder="pests"/>
                    </div>
                  
                    <button>create</button>
                    <p class="message">Already registered? <a href="<?php echo $login_page; ?>">Sign In</a></p>
                </form>
            </div>
        </div>
    </body>
</html>