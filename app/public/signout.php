<?php

session_start();

unset($_SESSION["user"]);
setcookie(name: "codelab_google_access_token", expires_or_options: time() - 3600);
setcookie(name: "codelab_google_refresh_token", expires_or_options: time() - 3600);
session_destroy();

header(header: "Location: signin.php");
exit();