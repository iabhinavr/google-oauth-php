<?php

function send_request($url, $data = null, $bearer = null): array {
    $response = [];

    $ch = curl_init();
    
    curl_setopt(handle: $ch, option: CURLOPT_URL, value: $url);
    curl_setopt(handle: $ch, option: CURLOPT_POST, value: true);

    if($data) {
        curl_setopt(handle: $ch, option: CURLOPT_POSTFIELDS, value: http_build_query(data: $data));
    }

    if($bearer) {
        curl_setopt(handle: $ch, option: CURLOPT_HTTPHEADER, value: array(
            'Authorization: Bearer ' . $bearer,
            'Accept: application/json'
        ));
    }
    
    curl_setopt(handle: $ch, option: CURLOPT_RETURNTRANSFER, value: true);
    $curl_response = curl_exec(handle: $ch);
    curl_close(handle: $ch);
    $response = json_decode(json: $curl_response, associative: true);
    
    return $response;
}

function exchange_code($code): bool {
    $is_exchanged = false;

    $data = array(
        'code' => $code,
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
        'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
        'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URI'],
        'grant_type' => 'authorization_code',
    );

    $exchange = send_request(url: "https://oauth2.googleapis.com/token", data: $data);

    if(isset($exchange['access_token']) && isset($exchange['refresh_token'])) {
        setcookie(name: 'codelab_google_access_token', value: $exchange['access_token'], httponly: true);
        setcookie(name: 'codelab_google_refresh_token', value: $exchange['refresh_token'], httponly: true);
        $is_exchanged = true;
    }
    return $is_exchanged;
}
function get_profile(): array {

    $profile = ["token_status" => null, "data" => null];

    if(isset($_SESSION["user"])) {
        $profile["data"] = $_SESSION["user"];
    }

    else if(isset($_COOKIE['codelab_google_access_token'])) {

        $fetch_profile = send_request(url: "https://openidconnect.googleapis.com/v1/userinfo", bearer: $_COOKIE['codelab_google_access_token']);

        if(isset($fetch_profile['error'])) {
            $profile["token_status"] = "invalid";
        }
        else {
            $profile["token_status"] = "valid";
            $profile["data"] = [
                "sub" => isset($fetch_profile["sub"]) ? $fetch_profile["sub"] : "",
                "name" => isset($fetch_profile["name"]) ? $fetch_profile["name"] : "",
                "given_name" => isset($fetch_profile["given_name"]) ? $fetch_profile["given_name"] : "",
                "picture" => isset($fetch_profile["picture"]) ? $fetch_profile["picture"] : "",
                "email" => isset($fetch_profile["email"]) ? $fetch_profile["email"] : "",
            ];
            $_SESSION["user"] = $profile["data"];
        }
    }

    return $profile;
}

function refresh_access_token(): bool {
    $is_refreshed = false;

    if(isset($_COOKIE["codelab_google_refresh_token"])) {

        $data = [
            'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
            'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'grant_type' => 'refresh_token',
            'refresh_token' => $_COOKIE['codelab_google_refresh_token'],
        ];

        $refresh_request = send_request(url: "https://oauth2.googleapis.com/token", data: $data);

        if(isset($refresh_request["access_token"])) {
            setcookie(name: "codelab_google_access_token", value: $refresh_request["access_token"], httponly: true);
            $is_refreshed = true;
        }

    }
    return $is_refreshed;
}

function verify_state($oauth_state): bool {
    
    if(empty($oauth_state)) {
        return false;
    }

    if(!isset($_SESSION["oauth_state"])) {
        return false;
    }

    if(!hash_equals(known_string: $_SESSION["oauth_state"], user_string: $oauth_state)) {
        return false;
    }

    return true;
}

function join_signin_parameters($data): string {
    $string = "?";
    foreach($data as $key => $value) {
        if($key === "redirect_uri") {
            $string .= "&$key=" . urlencode(string: $value);
        }
        else {
            $string .= "&$key=$value";
        }
        $string = ltrim(string: $string, characters: "&");
    }
    return $string;
}