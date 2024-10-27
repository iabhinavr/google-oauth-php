<?php

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->load();

if(isset($_GET) && isset($_GET['code'])) {
    $data = array(
        'code' => $_GET['code'],
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
        'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
        'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URI'],
        'grant_type' => 'authorization_code',
    );
    
    $ch = curl_init();
    
    curl_setopt(handle: $ch, option: CURLOPT_URL, value: 'https://oauth2.googleapis.com/token');
    curl_setopt(handle: $ch, option: CURLOPT_POST, value: true);
    curl_setopt(handle: $ch, option: CURLOPT_POSTFIELDS, value: http_build_query(data: $data));
    curl_setopt(handle: $ch, option: CURLOPT_RETURNTRANSFER, value: true);
    
    $response = curl_exec(handle: $ch);
    
    curl_close(handle: $ch);
    
    $token_data = json_decode(json: $response, associative: true);

    if(isset($token_data['access_token'])) {
        setcookie(name: 'codelab_google_access_token', value: $token_data['access_token'], httponly: true);
        header(header: 'Location: protected.php');
        exit();
    }
}