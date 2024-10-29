<?php

session_start();

if(!isset($_GET["token"]) || !isset($_SESSION["csrf_token"])) {
    echo "Invalid Attempt!";
    exit();
}

if(!hash_equals(known_string: $_SESSION["csrf_token"], user_string: $_GET["token"])) {
    echo "Invalid Token";
    exit();
}

$_SESSION = array();

setcookie(name: "codelab_google_access_token", expires_or_options: time() - 3600);
setcookie(name: "codelab_google_refresh_token", expires_or_options: time() - 3600);

if (ini_get(option: "session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(name: session_name(), value: '', expires_or_options: time() - 42000,
        path: $params["path"], domain: $params["domain"],
        secure: $params["secure"], httponly: $params["httponly"]
    );
}

session_destroy();

header(header: "Location: signin.php");
exit();