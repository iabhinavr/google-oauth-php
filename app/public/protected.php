<?php
session_start();

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: "../");
$dotenv->load();

include '../functions.php';

$user = authenticate();

if(($user["access_token"] === "invalid" || $user["access_token"] === null) && 
    $user["refresh_token"] === "exists" ) {
        
    $refresh_access_token = refresh_access_token();
    if($refresh_access_token) {
        $user = authenticate();
    }
}

if(!$user["data"] || empty($_SESSION["csrf_token"])) {

    header(header: 'Location: signin.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Page</title>
</head>
<body>
    <img src="<?= $user["data"]['picture'] ?>" alt="">
    <h1>Welcome, <?= $user["data"]['given_name'] ?></h1>
    <a href="/signout.php?token=<?= $_SESSION['csrf_token'] ?>" class="btn btn-primary btn-block">Signout</a>
</body>
</html>