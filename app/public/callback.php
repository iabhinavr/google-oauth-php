<?php

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: "../");
$dotenv->load();

include '../functions.php';

if(isset($_GET) && isset($_GET['code'])) {

    if(!verify_state(oauth_state: $_GET["state"])) {
        header(header: "Location: signin.php?error=invalid_state");
        exit();
    }

    $exchange_code = exchange_code(code: $_GET['code']);

    if(!$exchange_code) {
        header(header: 'Location: signin.php?error=token_exchange_error');
        exit();
    }

    header(header: 'Location: protected.php');

}