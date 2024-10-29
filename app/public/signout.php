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

unset($_SESSION["user"]);
unset($_SESSION["csrf_token"]);
setcookie(name: "codelab_google_access_token", expires_or_options: time() - 3600);
setcookie(name: "codelab_google_refresh_token", expires_or_options: time() - 3600);
session_destroy();

header(header: "Location: signin.php");
exit();