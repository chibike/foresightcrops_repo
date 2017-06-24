<?php
    require "../includes/php/config_script.php";
    require "../includes/php/login_user.php";
    
    $login_page = "../";
    if ( !current_user_is_logged_in() )
    {
        header('Location: '.$login_page);
    }
    
    $record_observation = "../..".constant("PAGES_DIR").'record_observation.php';
    $logout_screen = "logout_page.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Main Screen - Foresight Crops</title>
        <link rel="stylesheet" type="text/css" href="<?=constant("CSS_DIR")?>main_screen_style.css">
    </head>
    
    <body>
        <div class="buttons">
            <div class="top_button_row">
                <button>Risks Map</button>
                <button>Advice</button>
            </div>
            
            <div class="bottom_button_row">
                <a href="<?= $record_observation ?>"><button>Record Observation</button></a>
                <button>Community</button>
            </div>
            
            <p class="message">Done? <a href="<?=$logout_screen?>">Log out</a></p>
        </div>
    </body>
</html>