<?php

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: "../");
$dotenv->load();

include '../functions.php';

if(isset($_GET) && isset($_GET['code'])) {

    $exchange_code = exchange_code(code: $_GET['code']);

    if(!$exchange_code) {
        header(header: 'Location: signin.php?error=token_exchange_error');
        exit();
    }

    header(header: 'Location: protected.php');

}