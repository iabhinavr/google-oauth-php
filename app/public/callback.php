<?php
session_start();

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: "../");
$dotenv->load();

include '../functions.php';

if(isset($_GET) && isset($_GET['code'])) {

    $state_verification = verify_state(oauth_state: $_GET["state"]);

    if(!$state_verification["status"]) {
        header(header: "Location: signin.php?error=" . $state_verification["message"]);
        exit();
    }

    $exchange_code = exchange_code(code: $_GET['code']);

    if(!$exchange_code) {
        header(header: 'Location: signin.php?error=token_exchange_error');
        exit();
    }

    header(header: 'Location: protected.php');

}