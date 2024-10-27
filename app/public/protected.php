<?php
include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: "../");
$dotenv->load();

include '../functions.php';

$profile = get_profile();

if($profile["token_status"] === "invalid") {
    $refresh_access_token = refresh_access_token();
    if($refresh_access_token) {
        $profile = get_profile();
    }
}

if(!$profile["data"]) {

    header(header: 'Location: signin.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <img src="<?= $profile["data"]['picture'] ?>" alt="">
    <h1>Welcome, <?= $profile["data"]['given_name'] ?></h1>
</body>
</html>