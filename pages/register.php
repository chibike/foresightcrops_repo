<?php
    require "../includes/php/login_user.php";
    require "../includes/php/config_script.php";
    
    $next_page = "../..".constant("PAGES_DIR").'main_screen.php';
    $login_page = '/';
    
    $firstname_error = $lastname_error = $email_error = $password_error = '';
    $address_error = $city_error = $state_error = $country_error = $overall_error = '';
    
    $email = parse_input($_POST["email"]);
    $firstname = parse_input($_POST["firstname"]);
    $lastname = parse_input($_POST["lastname"]);
    $password = '';
    $address = parse_input($_POST["address"]);
    $city = parse_input($_POST["city"]);
    $state = parse_input($_POST["state"]);
    $country = parse_input($_POST["country"]);
    
    $empty_err_msg = "*This field is required";
    $password_err_msg = "Password must be between 8-14 characters long";
    $email_err_msg = "Invaild email format";
    $name_err_msg = "Only letters and white spaces allowed";
    $address_err_msg = "Invaild address";
    $city_err_msg = "Invalid city name";
    $state_err_msg = "Invaild state name";
    $country_err_msg = "Invalid country name";
    
    $err_logged = false;
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $new_password = extractValidPassword( $_POST["password"], $_POST["password_again"], 8, 15 );
        if( $new_password == "" )
        {
            $password_error = $password_err_msg;
            $err_logged = true;
        }
        else
        {
            $password = $new_password;
        }
        
        if( !containsOnlyAlphaCharacters($firstname) )
        {
            $firstname_error = $name_err_msg;
            $err_logged = true;
        }
        
        if( !containsOnlyAlphaCharacters( $lastname ) )
        {
            $lastname_error = $name_err_msg;
            $err_logged = true;
        }
        
        if( !isValidEmail( $email ) )
        {
            $email_error = $email_err_msg;
            $err_logged = true;
        }
        
        if ( !containsOnlyAlphaCharacters($city) )
        {
            $city_error = $city_err_msg;
            $address_error = $address_err_msg;
            $err_logged = true;
        }
        
        if ( !containsOnlyAlphaCharacters($state) )
        {
            $state_error = $state_err_msg;
            $address_error = $address_err_msg;
            $err_logged = true;
        }
        
        if ( !containsOnlyAlphaCharacters($country) )
        {
            $country_error = $country_err_msg;
            $address_error = $address_err_msg;
            $err_logged = true;
        }
        
        if( !$err_logged )
        {
            if ( register_user($firstname, $lastname, $email, $password, $city, $state, $country) )
            {
                if ( login($email, $password) )
                {
                    header('Location: '.$next_page);
                    die();
                }
                else
                {
                    $overall_error = 'Sorry could not login (Invaild details)';
                }
            }
            else
            {
                $overall_error = 'Sorry could not login (user exists)';
            }
        }
    }
    
    function containsOnlyAlphaCharacters($str)
    {
        $str = parse_input($str);
        
        if ( empty($str) )
        {
            return false;
        }
        else if ( preg_match( "/^[a-zA-Z ]*$/", $str) )
        {
            return true;
        }
        else
        {
            return false;
        }
        return false;
    }
    
    function isValidEmail($str)
    {
        $str = parse_input($str);
        
        if ( empty($str) )
        {
            return false;
        }
        else if (filter_var($str, FILTER_VALIDATE_EMAIL))
        {
            return true;
        }
        else
        {
            return false;
        }
        
        return false;
    }
    
    function extractValidPassword($pass, $pass_comfirm, $min_len, $max_len)
    {
        $pass = parse_input($pass);
        $pass_comfirm = parse_input($pass_comfirm);
        
        if ( empty($pass) || empty($pass_comfirm) )
        {
            return "";
        }
        else if ($pass != $pass_comfirm)
        {
            return "";
        }
        else if ( strlen($pass) < $min_len || strlen($pass) > $max_len )
        {
            return "";
        }
        else
        {
            return md5($pass);
        }
    }
    
    function register_user($firstname, $lastname, $email, $password, $city, $state, $country)
    {
        if ( !validate_email($email) || !validate_name($firstname, $lastname) || !validate_password($password) )
        {
            return false;
        }
        
        return add_user_to_database($firstname, $lastname, $email, $password, $city, $state, $country);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Registration Beta - Foresight Crops</title>
        <link rel="stylesheet" type="text/css" href="<?=constant("CSS_DIR")?>register.css">
        <script src="<?=constant("JS_DIR")?>register.js"></script>
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
                        <span class="error"><?= $firstname_error ?></span>
                        <input name="firstname" type="text" placeholder="firstname" value="<?php echo $firstname; ?>"  autofocus required />
                        
                        <span class="error"><?= $lastname_error ?></span>
                        <input name="lastname" type="text" placeholder="lastname" value="<?php echo $lastname; ?>" required />
                        
                        <span class="error"><?= $email_error ?></span>
                        <input name="email" type="text" placeholder="email address" value="<?php echo $email; ?>" required />
                        
                        <span class="error"><?= $password_error ?></span>
                        <input name="password" type="password" placeholder="password" required />
                        <input name="password_again" type="password" placeholder="password again" required />
                        
                        <span class="error"><?= $overall_error ?></span>
                    </div>
                  
                    <div class="location-form" id="address">
                        <label>Address</label>
                        <!-- Search Field -->
                        <div class="search-field" id="locationField">
                            <span class="error"><?= $address_error ?></span>
                            <input name="address" class="address-field" id="autocomplete" placeholder="address or post_code" onFocus="geolocate()" type="text" value="<?php echo $address?>" />
                        </div>
                        
                        <div class="disabled">
		                    <!-- City -->
		                    <div class="city-field">
                                <span class="error"><?= $city_error ?></span>
		                        <input name="city" type="text" placeholder="city" id="locality" onkeypress="return false;" value="<?php echo $city?>" required />
		                    </div>

		                    <!-- State -->
		                    <div class="state-field">
                                <span class="error"><?= $state_error ?></span>
		                        <input name="state" type="text" placeholder="state" id="administrative_area_level_1" onkeypress="return false;" value="<?php echo $state?>" required />
		                    </div>
		                    
		                    <!-- Country -->
		                    <div class="country-field">
                                <span class="error"><?= $country_error ?></span>
		                        <input name="country" type="text" placeholder="country" id="country" onkeypress="return false;" value="<?php echo $country?>" required />
		                    </div>
		                </div>
                    </div>
                  
                    <div class="pests-crops-form">
                        <div>
                            <label>Crops</label>
                            <input name="crops" type="text" placeholder="crops" list="crops_list"/>
                            <datalist id="crops_list">
                                <option value="Apples">
                                <option value="Bananas">
                                <option value="Cabbages">
                                <option value="Dewberries">
                                <option value="Elderberries">
                            </datalist>
                        </div>
                        
                        <div>
                            <label>Common Pests</label>
                            <input name="pests" type="text" placeholder="pests" list="pests_list"/>
                            <datalist id="pests_list">
                                <option value="Ants">
                                <option value="Bees">
                                <option value="Centipedes">
                                <option value="Drugstore beetles">
                                <option value="Earthworms">
                            </datalist>
                        </div>
                    </div>
                  
                    <button>create</button>
                    <p class="message">Already registered? <a href="<?php echo $login_page; ?>">Sign In</a></p>
                </form>
            </div>
        </div>
    </body>
</html>