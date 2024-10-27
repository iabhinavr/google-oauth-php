<?php
include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->load();

$authenticated = false;

if(isset($_COOKIE['codelab_google_access_token'])) {
    $api_url = 'https://openidconnect.googleapis.com/v1/userinfo';

    $ch = curl_init();

    curl_setopt(handle: $ch, option: CURLOPT_URL, value: $api_url);
    curl_setopt(handle: $ch, option: CURLOPT_HTTPHEADER, value: array(
        'Authorization: Bearer ' . $_COOKIE['codelab_google_access_token'],
        'Accept: application/json'
    ));

    curl_setopt(handle: $ch, option: CURLOPT_RETURNTRANSFER, value: true);

    $response = curl_exec(handle: $ch);

    curl_close(handle: $ch);

    $user_data = json_decode(json: $response, associative: true);

    if(
        isset($user_data['sub']) && 
        isset($user_data['name']) &&
        isset($user_data['given_name']) &&
        isset($user_data['picture']) &&
        isset($user_data['email'])
    ) {
        $authenticated = true;
    }
}

if(!$authenticated) {
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
    <?php if($authenticated) : ?>
        <img src="<?= $user_data['picture'] ?>" alt="">
        <h1>Welcome, <?= $user_data['given_name'] ?></h1>
    <?php else: ?>
        <a href="signin.php">Please login</a>
    <?php endif; ?>
</body>
</html>